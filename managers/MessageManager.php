<?php

namespace Managers;

use Models\Message;
use Services\Database;

class MessageManager
{
    public function findBySalon($salonId)
    {
        $pdo = Database::getConnection();
        $statement = $pdo->prepare('SELECT id, salon_id, content, is_pinned, created_at FROM messages WHERE salon_id = :salon_id ORDER BY is_pinned DESC, created_at ASC');
        $statement->execute(['salon_id' => $salonId]);
        $rows = $statement->fetchAll();

        $messages = [];

        foreach ($rows as $row) {
            $messages[] = new Message($row['id'], $row['salon_id'], $row['content'], $row['is_pinned'], $row['created_at']);
        }

        return $messages;
    }

    public function create($salonId, $content)
    {
        $pdo = Database::getConnection();
        $statement = $pdo->prepare('INSERT INTO messages(salon_id, content) VALUES(:salon_id, :content)');
        $statement->execute([
            'salon_id' => $salonId,
            'content'  => $content,
        ]);
    }

    public function pinOne($salonId, $messageId)
    {
        $pdo = Database::getConnection();

        $resetStmt = $pdo->prepare('UPDATE messages SET is_pinned = 0 WHERE salon_id = :salon_id');
        $resetStmt->execute(['salon_id' => $salonId]);

        $pinStmt = $pdo->prepare('UPDATE messages SET is_pinned = 1 WHERE id = :id AND salon_id = :salon_id');
        $pinStmt->execute([
            'id'       => $messageId,
            'salon_id' => $salonId,
        ]);
    }
}
