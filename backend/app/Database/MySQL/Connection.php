<?php

declare(strict_types=1);

namespace Database\MySQL;

use PDO;
use PDOException;
use System;

class Connection
{

    private $pdo;

    public function __construct($hostname, $username, $password, $database)
    {
        try {
            $this->pdo = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            System::dropError("Connection failed: " . $e->getMessage());
            die();
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}
