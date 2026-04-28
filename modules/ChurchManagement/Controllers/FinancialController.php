<?php

declare(strict_types=1);

namespace Modules\ChurchManagement\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Database;
use App\Models\FinancialTransaction;
use App\Models\User;

class FinancialController extends Controller
{
    private function orgId(): int 
    { 
        $org = Session::get('organization');
        if (is_array($org) && !empty($org['id'])) {
            return (int) $org['id'];
        }

        $user = Session::user() ?? [];
        $userId = (int) ($user['id'] ?? 0);
        if ($userId > 0) {
            try {
                $dbOrg = User::getOrganization($userId);
                if ($dbOrg) {
                    Session::set('organization', [
                        'id'        => $dbOrg['id'],
                        'name'      => $dbOrg['name'],
                        'slug'      => $dbOrg['slug'] ?? '',
                        'type'      => $dbOrg['type'] ?? '',
                        'plan'      => $dbOrg['plan'] ?? 'trial',
                        'status'    => $dbOrg['status'] ?? 'trial',
                        'role_slug' => $dbOrg['role_slug'] ?? null,
                        'role_name' => $dbOrg['role_name'] ?? null,
                    ]);
                    return (int) $dbOrg['id'];
                }
            } catch (\Throwable $e) {}
        }

        return 0;
    }

    private function churchUnits(): array
    {
        try {
            $stmt = Database::connection()->prepare('SELECT * FROM church_units WHERE organization_id = :org_id ORDER BY status ASC, name ASC');
            $stmt->execute(['org_id' => $this->orgId()]);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function index(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $page = (int) ($request->input('page', '1'));
            $filters = [
                'type'       => $request->input('type', ''),
                'start_date' => $request->input('start_date', date('Y-m-01')),
                'end_date'   => $request->input('end_date', date('Y-m-t')),
            ];
            $result = FinancialTransaction::byOrg($orgId, $filters, $page);
            $summary = FinancialTransaction::summary($orgId, $filters['start_date'], $filters['end_date']);
            $categories = FinancialTransaction::getCategories($orgId);

            if (($result['degraded'] ?? false) === true || ($summary['degraded'] ?? false) === true) {
                Session::flash('warning', 'Financeiro indisponivel no momento. Exibindo modo de contingencia.');
            }

            $this->view('management/financial/index', [
                'pageTitle'    => 'Financeiro — Gestão',
                'breadcrumb'   => 'Financeiro',
                'transactions' => $result['data'],
                'pagination'   => $result,
                'summary'      => $summary,
                'categories'   => $categories,
                'filters'      => $filters,
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar financeiro: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    public function categories(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Categorias Financeiras', 'categorias-financeiras');
            
            $categories = FinancialTransaction::getCategories($this->orgId());

            $this->view('management/financial/categories', array_merge($context, [
                'pageTitle' => 'Categorias Financeiras — Gestão',
                'categories' => $categories
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function create(Request $request): void
    {
        try {
            $this->view('management/financial/form', [
                'pageTitle'   => 'Nova transação — Gestão',
                'breadcrumb'  => 'Financeiro / Nova',
                'transaction' => null,
                'categories'  => FinancialTransaction::getCategories($this->orgId()),
                'units'       => $this->churchUnits(),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar formulário: ' . $e->getMessage());
            redirect('/gestao/receitas');
        }
    }

    public function store(Request $request): void
    {
        $this->validate($request, [
            'type'             => 'required',
            'description'      => 'required|min:3',
            'amount'           => 'required',
            'transaction_date' => 'required',
        ]);

        $data = $request->only([
            'type','category_id','description','amount','transaction_date','reference','status','notes','church_unit_id'
        ]);
        $data['church_unit_id'] = (int) ($data['church_unit_id'] ?? 0) ?: null;

        FinancialTransaction::create(array_merge($data, [
            'organization_id' => $this->orgId(),
            'created_by'      => Session::user()['id'],
        ]));

        Session::flash('success', 'Transação registrada.');
        redirect($request->input('type') === 'expense' ? '/gestao/despesas' : '/gestao/receitas');
    }

    public function createCategory(Request $request): void
    {
        $this->validate($request, ['name' => 'required', 'type' => 'required']);
        $pdo = Database::connection();
        $stmt = $pdo->prepare("INSERT INTO financial_categories (organization_id, name, type, color) VALUES (:org, :name, :type, :color)");
        $stmt->execute([
            'org'   => $this->orgId(),
            'name'  => $request->input('name'),
            'type'  => $request->input('type'),
            'color' => $request->input('color', '#0A4DFF'),
        ]);
        Session::flash('success', 'Categoria criada.');
        redirect('/gestao/configuracoes/categorias');
    }
}
