<?php

declare(strict_types=1);

namespace App\Managers;

use App\Models\Salon;
use App\Services\Database;

class SalonManager
{
    public function findAll(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT id, name, created_at FROM salons ORDER BY name ASC');

        $salons = [];
        foreach ($stmt->fetchAll() as $row) {
            $salons[] = new Salon((int) $row['id'], $row['name'], $row['created_at']);
        }

        return $salons;
    }

    public function findById(int $id): ?Salon
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT id, name, created_at FROM salons WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new Salon((int) $row['id'], $row['name'], $row['created_at']);
    }

    public function create(string $name): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO salons(name) VALUES(:name)');
        $stmt->execute(['name' => $name]);

        return (int) $pdo->lastInsertId();
    }

    public function findByName(string $name): ?Salon
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT id, name, created_at FROM salons WHERE name = :name LIMIT 1');
        $stmt->execute(['name' => $name]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new Salon((int) $row['id'], $row['name'], $row['created_at']);
    }
}
