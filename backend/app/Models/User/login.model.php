<?php

use Firebase\JWT\Key;

require_once(VN_APP_PATH . "/Database/MySQL/Connection.php");

use Database\MySQL\Connection;
use Firebase\JWT\JWT;

class LoginModel
{
    private static $connection;
    private static $userInfo;

    public static function loginUser(string $email, string $password)
    {
        try {
            self::$connection = new Connection("localhost", "root", "", "eduRaspored");

            $pdo = self::$connection->getPdo();

            $user = self::getUserByEmail($email, $pdo);
            self::$userInfo = $user;

            if (!$user) {
                return null;
            }

            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            Response::setStatus(500);
            Response::setJsonContent(["error" => "Error logging in: " . $e->getMessage()]);
            return null;
        }
    }

    private static function getUserByEmail(string $email, $pdo)
    {
        try {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Response::setStatus(500);
            Response::setJsonContent(["error" => "Error getting user by email: " . $e->getMessage()]);
            return null;
        }
    }

    // ...
    public static function getUserToken($userId)
    {
        try {
            $pdo = self::$connection->getPdo();

            $stmt = $pdo->prepare('SELECT token FROM users WHERE id = :id');
            $stmt->bindParam(':id', $userId);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return null;
        }
    }
    // ...

    // ...
    public static function isTokenActive($userId)
    {
        try {
            $pdo = self::$connection->getPdo();

            $stmt = $pdo->prepare('SELECT token_active FROM users WHERE id = :id');
            $stmt->bindParam(':id', $userId);
            $stmt->execute();

            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            return false;
        }
    }
    // ...

    // ...
    public static function setTokenActive($userId)
    {
        try {
            $pdo = self::$connection->getPdo();

            $stmt = $pdo->prepare('UPDATE users SET token_active = 1 WHERE id = :id');
            $stmt->bindParam(':id', $userId);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    // ...

    // ...
    public static function isTokenExpired($token)
    {
        $jwtSecretKey = "your_secret_key";

        try {
            $decoded = JWT::decode($token, new Key($jwtSecretKey, 'HS256'));
            $currentTimestamp = time();

            if ($decoded->exp < $currentTimestamp) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return true;
        }
    }
    // ...

    // ...
    public static function updateToken($userId, $username, $email)
    {
        try {
            $pdo = self::$connection->getPdo();

            // ...
            // Generate JWT token
            $jwtSecretKey = "your_secret_key"; // Ovo je samo DEMO
            $tokenData = array(
                "id" => $userId,
                "username" => $username,
                "email" => $email,
                "exp" => time() + 3600 // Set the expiration time (e.g., 1 hour from now)
            );
            $token = JWT::encode($tokenData, $jwtSecretKey, "HS256");
            // ...

            $stmt = $pdo->prepare('UPDATE users SET token = :token, token_active = 1 WHERE id = :id');
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':id', $userId);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    // ...

}
