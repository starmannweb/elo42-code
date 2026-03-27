<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Ticket extends Model
{
    protected static string $table = 'tickets';
    protected static array $fillable = ['user_id','organization_id','subject','description','category','priority','status','assigned_to','resolved_at','closed_at'];

    public static function allAdmin(array $filters = []): array
    {
        $pdo = Database::connection();
        $where = '1=1';
        $params = [];
        if (!empty($filters['status'])) { $where .= " AND t.status = :st"; $params['st'] = $filters['status']; }
        if (!empty($filters['priority'])) { $where .= " AND t.priority = :pr"; $params['pr'] = $filters['priority']; }
        $stmt = $pdo->prepare("SELECT t.*, u.name as user_name, u.email as user_email, o.name as org_name FROM tickets t JOIN users u ON t.user_id = u.id LEFT JOIN organizations o ON t.organization_id = o.id WHERE {$where} ORDER BY FIELD(t.priority,'urgent','high','normal','low'), t.created_at DESC");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getReplies(int $ticketId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT tr.*, u.name as user_name FROM ticket_replies tr JOIN users u ON tr.user_id = u.id WHERE tr.ticket_id = :tid ORDER BY tr.created_at ASC");
        $stmt->execute(['tid' => $ticketId]);
        return $stmt->fetchAll();
    }

    public static function countOpen(): int
    {
        $pdo = Database::connection();
        return (int) $pdo->query("SELECT COUNT(*) FROM tickets WHERE status IN ('open','in_progress')")->fetchColumn();
    }
}
