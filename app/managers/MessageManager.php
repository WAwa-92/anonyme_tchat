<?php

declare(strict_types=1);

namespace App\Managers;

use App\Models\Message;
use App\Services\Database;
use Throwable;

class MessageManager
{
    public function findBySalon(int $salonId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT id, salon_id, content, is_pinned, created_at FROM messages WHERE salon_id = :salon_id ORDER BY is_pinned DESC, created_at ASC');
        $stmt->execute(['salon_id' => $salonId]);

        $messages = [];
        foreach ($stmt->fetchAll() as $row) {
            $messages[] = new Message(
                (int) $row['id'],
                (int) $row['salon_id'],
                $row['content'],
                (bool) $row['is_pinned'],
                $row['created_at']
            );
        }

        return $messages;
    }

    public function create(int $salonId, string $content): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO messages(salon_id, content) VALUES(:salon_id, :content)');
        $stmt->execute([
            'salon_id' => $salonId,
            'content' => $content,
        ]);
    }

    public function pinOne(int $salonId, int $messageId): void
    {
        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $resetStmt = $pdo->prepare('UPDATE messages SET is_pinned = 0 WHERE salon_id = :salon_id');
            $resetStmt->execute(['salon_id' => $salonId]);

            $pinStmt = $pdo->prepare('UPDATE messages SET is_pinned = 1 WHERE id = :id AND salon_id = :salon_id');
            $pinStmt->execute([
                'id' => $messageId,
                'salon_id' => $salonId,
            ]);

            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $exception;
        }
    }

    public function existsInSalon(int $messageId, int $salonId): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT id FROM messages WHERE id = :id AND salon_id = :salon_id LIMIT 1');
        $stmt->execute([
            'id' => $messageId,
            'salon_id' => $salonId,
        ]);

        return (bool) $stmt->fetch();
    }
}
