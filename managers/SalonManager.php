<?php

namespace Managers;

use Models\Salon;
use Services\Database;

class SalonManager
{
    public function findAll()
    {
        $pdo = Database::getConnection();
        $statement = $pdo->query('SELECT id, name, created_at FROM salons ORDER BY name ASC');
        $rows = $statement->fetchAll();

        $salons = [];

        foreach ($rows as $row) {
            $salons[] = new Salon($row['id'], $row['name'], $row['created_at']);
        }

        return $salons;
    }

    public function findById($id)
    {
        $pdo = Database::getConnection();
        $statement = $pdo->prepare('SELECT id, name, created_at FROM salons WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        if (!$row) {
            return null;
        }

        return new Salon($row['id'], $row['name'], $row['created_at']);
    }

    public function create($name)
    {
        $pdo = Database::getConnection();
        $statement = $pdo->prepare('INSERT INTO salons(name) VALUES(:name)');
        $statement->execute(['name' => $name]);

        return $pdo->lastInsertId();
    }
}
