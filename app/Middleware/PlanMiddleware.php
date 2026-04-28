<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Session;

class PlanMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next, string ...$params): void
    {
        $requiredPlan = $params[0] ?? null;

        if (!$requiredPlan) {
            $next();
            return;
        }

        $organization = Session::get('organization');
        $user = Session::user() ?? [];
        $currentPlan = is_array($organization) ? (string) ($organization['plan'] ?? 'free') : 'free';
        $roleSlug = is_array($organization) ? (string) ($organization['role_slug'] ?? '') : '';
        $permissions = is_array($user['permissions'] ?? null) ? $user['permissions'] : [];
        $premiumPlans = ['premium', 'professional', 'enterprise'];
        $isPremiumLike = in_array($currentPlan, $premiumPlans, true);
        $isSystemAdmin = in_array($roleSlug, ['super-admin', 'admin-elo42'], true)
            || in_array('admin.access', $permissions, true)
            || strtolower((string) ($user['email'] ?? '')) === 'ricieri@starmannweb.com.br';

        // Trial grace period (7 days from user creation)
        $isTrialActive = false;
        if ($currentPlan === 'free' && !empty($user['created_at'])) {
            try {
                $created = new \DateTimeImmutable($user['created_at']);
                $deadline = $created->modify('+7 days');
                $now = new \DateTimeImmutable('now');
                if ($now < $deadline) {
                    $isTrialActive = true;
                }
            } catch (\Throwable $e) {}
        }

        // Lógica: se exige premium e está no free (e fora do trial), barra. 
        if ($requiredPlan === 'premium' && !$isPremiumLike && !$isSystemAdmin && !$isTrialActive) {
            if ($request->isAjax() || str_starts_with($request->uri(), '/api/')) {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Recurso restrito ao Plano Premium.']);
                exit;
            }

            Session::flash('warning', 'Este recurso é exclusivo do Plano Premium. Assine agora para desbloquear!');
            redirect('/gestao/assinatura');
        }

        $next();
    }
}
