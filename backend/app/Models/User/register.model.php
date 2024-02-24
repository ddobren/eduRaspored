<?php

require_once(VN_APP_PATH . "/Database/MySQL/Connection.php");

use Database\MySQL\Connection;
use Firebase\JWT\JWT;

class RegisterModel
{
    private static $connection;

    public static function addNewUser(string $username, string $email, string $password)
    {
        try {
            self::$connection = new Connection("localhost", "root", "", "eduRaspored");

            $pdo = self::$connection->getPdo();

            if (self::checkIfUsernameExists($username, $pdo)) {
                return;
            }

            if (self::checkIfEmailExists($email, $pdo)) {
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $userId = self::insertUser($pdo, $username, $email, $hashedPassword);

            if (!$userId) {
                Response::setStatus(500);
                Response::setJsonContent(["error" => "Error adding user"]);
                return;
            }

            // Generate JWT token
            $jwtSecretKey = "your_secret_key"; // Ovo je DEMO
            $tokenData = array(
                "id" => $userId,
                "username" => $username,
                "email" => $email,
                "exp" => time() + 3600
            );
            $token = JWT::encode($tokenData, $jwtSecretKey, "HS256");
            // ...

            self::updateToken($pdo, $userId, $token);

            Response::setStatus(200);
            Response::setJsonContent(["success" => "User successfully added!!!", "token" => $token]);
        } catch (PDOException $e) {
            Response::setStatus(500);
            Response::setJsonContent(["error" => "Error adding user: " . $e->getMessage()]);
        }
    }

    private static function updateToken($pdo, $userId, $token)
    {
        $stmt = $pdo->prepare('UPDATE users SET token = :token, token_active = 1 WHERE id = :id');
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $userId);

        $stmt->execute();
    }

    private static function checkIfUsernameExists(string $username, $pdo): bool
    {
        try {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $count = $stmt->fetchColumn();

            if ($count > 0) {
                Response::setStatus(500);
                Response::setJsonContent(["error" => "Username already exists!!!"]);
                return true;
            }

            Response::setStatus(200);
            return false;
        } catch (PDOException $e) {
            Response::setStatus(500);
            Response::setJsonContent(["error" => "Error checking username: " . $e->getMessage()]);
            return true;
        }
    }

    private static function checkIfEmailExists(string $email, $pdo): bool
    {
        try {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $count = $stmt->fetchColumn();

            if ($count > 0) {
                Response::setStatus(500);
                Response::setJsonContent(["error" => "Email already exists!!!"]);
                return true;
            }

            Response::setStatus(200);
            return false;
        } catch (PDOException $e) {
            Response::setStatus(500);
            Response::setJsonContent(["error" => "Error checking email: " . $e->getMessage()]);
            return true;
        }
    }

    private static function insertUser($pdo, string $username, string $email, string $hashedPassword)
    {
        try {
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

            $stmt->execute();

            return $pdo->lastInsertId(); // Vrati ID novododanog korisnika
        } catch (PDOException $e) {
            return false;
        }
    }
}
