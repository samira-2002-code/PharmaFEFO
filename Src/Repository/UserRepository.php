<?php

namespace App\Repository;

use App\Entity\StockBatch;
use PDO;


class UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public  function login($email, $password)
    {
        $stmt =  $this->pdo->prepare("
        SELECT
            users.*,
            roles.label AS role
        FROM users
        INNER JOIN roles
            ON users.role_id = roles.id
        WHERE users.email = ?
    ");

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user->password)) {
            return false;
        }

        return $user;
    }
}


