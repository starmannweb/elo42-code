<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class ActionPlan extends Model
{
    protected static string $table = 'action_plans';
    protected static array $fillable = ['organization_id','title','description','start_date','end_date','status','created_by'];

    public static function byOrg(int $orgId): array
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("SELECT ap.*, (SELECT COUNT(*) FROM action_plan_objectives apo WHERE apo.plan_id = ap.id) as objective_count, (SELECT COUNT(*) FROM action_plan_tasks apt JOIN action_plan_objectives apo2 ON apt.objective_id = apo2.id WHERE apo2.plan_id = ap.id) as task_count, (SELECT COUNT(*) FROM action_plan_tasks apt2 JOIN action_plan_objectives apo3 ON apt2.objective_id = apo3.id WHERE apo3.plan_id = ap.id AND apt2.status = 'done') as tasks_done FROM action_plans ap WHERE ap.organization_id = :org ORDER BY ap.created_at DESC");
            $stmt->execute(['org' => $orgId]);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            static::logModelFailure('byOrg', $e, ['organization_id' => $orgId]);
            return [];
        }
    }

    public static function getWithDetails(int $planId): ?array
    {
        try {
            $pdo = Database::connection();
            $plan = $pdo->prepare("SELECT * FROM action_plans WHERE id = :id");
            $plan->execute(['id' => $planId]);
            $p = $plan->fetch();
            if (!$p) {
                return null;
            }

            $objs = $pdo->prepare("SELECT * FROM action_plan_objectives WHERE plan_id = :pid ORDER BY sort_order");
            $objs->execute(['pid' => $planId]);
            $p['objectives'] = $objs->fetchAll();

            foreach ($p['objectives'] as &$obj) {
                $tasks = $pdo->prepare("SELECT t.*, m.name as assigned_name FROM action_plan_tasks t LEFT JOIN members m ON t.assigned_to = m.id WHERE t.objective_id = :oid ORDER BY t.sort_order");
                $tasks->execute(['oid' => $obj['id']]);
                $obj['tasks'] = $tasks->fetchAll();
            }

            return $p;
        } catch (\Throwable $e) {
            static::logModelFailure('getWithDetails', $e, ['plan_id' => $planId]);
            return null;
        }
    }

    public static function pendingTasks(int $orgId): int
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM action_plan_tasks apt JOIN action_plan_objectives apo ON apt.objective_id = apo.id JOIN action_plans ap ON apo.plan_id = ap.id WHERE ap.organization_id = :org AND apt.status != 'done' AND ap.status = 'active'");
            $stmt->execute(['org' => $orgId]);
            return (int) $stmt->fetchColumn();
        } catch (\Throwable $e) {
            static::logModelFailure('pendingTasks', $e, ['organization_id' => $orgId]);
            return 0;
        }
    }
}
