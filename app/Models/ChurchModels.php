<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class ChurchRequest extends Model
{
    protected static string $table = 'requests';
    protected static array $fillable = ['organization_id','member_id','title','description','type','priority','status','resolved_at','created_by'];

    public static function byOrg(int $orgId, ?string $status = null): array
    {
        $pdo = Database::connection();
        $sql = "SELECT r.*, m.name as member_name FROM requests r LEFT JOIN members m ON r.member_id = m.id WHERE r.organization_id = :org";
        $params = ['org' => $orgId];
        if ($status) { $sql .= " AND r.status = :st"; $params['st'] = $status; }
        $sql .= " ORDER BY FIELD(r.priority,'urgent','high','normal','low'), r.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function countOpen(int $orgId): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM requests WHERE organization_id = :org AND status IN ('open','in_progress')");
        $stmt->execute(['org' => $orgId]);
        return (int) $stmt->fetchColumn();
    }
}

class Visit extends Model
{
    protected static string $table = 'visits';
    protected static array $fillable = ['organization_id','visitor_name','phone','email','visit_date','source','notes','follow_up','assigned_to'];

    public static function byOrg(int $orgId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT v.*, m.name as assigned_name FROM visits v LEFT JOIN members m ON v.assigned_to = m.id WHERE v.organization_id = :org ORDER BY v.visit_date DESC");
        $stmt->execute(['org' => $orgId]);
        return $stmt->fetchAll();
    }
}

class CounselingSession extends Model
{
    protected static string $table = 'counseling_sessions';
    protected static array $fillable = ['organization_id','member_id','counselor_name','subject','session_date','status','notes','is_confidential'];

    public static function byOrg(int $orgId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT cs.*, m.name as member_name FROM counseling_sessions cs LEFT JOIN members m ON cs.member_id = m.id WHERE cs.organization_id = :org ORDER BY cs.session_date DESC");
        $stmt->execute(['org' => $orgId]);
        return $stmt->fetchAll();
    }
}

class Sermon extends Model
{
    protected static string $table = 'sermons';
    protected static array $fillable = ['organization_id','title','preacher','sermon_date','bible_reference','summary','series_name','tags','status'];

    public static function byOrg(int $orgId, ?string $search = null): array
    {
        $pdo = Database::connection();
        $sql = "SELECT * FROM sermons WHERE organization_id = :org";
        $params = ['org' => $orgId];
        if ($search) { $sql .= " AND (title LIKE :s OR preacher LIKE :s OR bible_reference LIKE :s)"; $params['s'] = "%{$search}%"; }
        $sql .= " ORDER BY sermon_date DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}

class ActionPlan extends Model
{
    protected static string $table = 'action_plans';
    protected static array $fillable = ['organization_id','title','description','start_date','end_date','status','created_by'];

    public static function byOrg(int $orgId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT ap.*, (SELECT COUNT(*) FROM action_plan_objectives apo WHERE apo.plan_id = ap.id) as objective_count, (SELECT COUNT(*) FROM action_plan_tasks apt JOIN action_plan_objectives apo2 ON apt.objective_id = apo2.id WHERE apo2.plan_id = ap.id) as task_count, (SELECT COUNT(*) FROM action_plan_tasks apt2 JOIN action_plan_objectives apo3 ON apt2.objective_id = apo3.id WHERE apo3.plan_id = ap.id AND apt2.status = 'done') as tasks_done FROM action_plans ap WHERE ap.organization_id = :org ORDER BY ap.created_at DESC");
        $stmt->execute(['org' => $orgId]);
        return $stmt->fetchAll();
    }

    public static function getWithDetails(int $planId): ?array
    {
        $pdo = Database::connection();
        $plan = $pdo->prepare("SELECT * FROM action_plans WHERE id = :id");
        $plan->execute(['id' => $planId]);
        $p = $plan->fetch();
        if (!$p) return null;

        $objs = $pdo->prepare("SELECT * FROM action_plan_objectives WHERE plan_id = :pid ORDER BY sort_order");
        $objs->execute(['pid' => $planId]);
        $p['objectives'] = $objs->fetchAll();

        foreach ($p['objectives'] as &$obj) {
            $tasks = $pdo->prepare("SELECT t.*, m.name as assigned_name FROM action_plan_tasks t LEFT JOIN members m ON t.assigned_to = m.id WHERE t.objective_id = :oid ORDER BY t.sort_order");
            $tasks->execute(['oid' => $obj['id']]);
            $obj['tasks'] = $tasks->fetchAll();
        }
        return $p;
    }

    public static function pendingTasks(int $orgId): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM action_plan_tasks apt JOIN action_plan_objectives apo ON apt.objective_id = apo.id JOIN action_plans ap ON apo.plan_id = ap.id WHERE ap.organization_id = :org AND apt.status != 'done' AND ap.status = 'active'");
        $stmt->execute(['org' => $orgId]);
        return (int) $stmt->fetchColumn();
    }
}
