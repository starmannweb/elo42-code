<?php

declare(strict_types=1);

namespace App\Features\MinistryAi;

final class GenerationRepository
{
    public static function makeTempId(): string
    {
        return 'temp-' . date('YmdHis') . '-' . bin2hex(random_bytes(4));
    }

    public static function prepareRecord(array $data): array
    {
        $now = date('c');

        return [
            'id' => (string) ($data['id'] ?? self::makeTempId()),
            'userId' => $data['userId'] ?? null,
            'organizationId' => $data['organizationId'] ?? null,
            'module' => (string) ($data['module'] ?? ''),
            'workflowId' => (string) ($data['workflowId'] ?? ''),
            'title' => (string) ($data['title'] ?? 'Geracao ministerial'),
            'inputPayload' => $data['inputPayload'] ?? [],
            'outputMarkdown' => (string) ($data['outputMarkdown'] ?? ''),
            'modelUsed' => (string) ($data['modelUsed'] ?? 'offline'),
            'status' => (string) ($data['status'] ?? 'completed'),
            'createdAt' => (string) ($data['createdAt'] ?? $now),
            'updatedAt' => (string) ($data['updatedAt'] ?? $now),
        ];
    }
}
