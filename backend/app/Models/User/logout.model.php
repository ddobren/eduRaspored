<?php

require_once(VN_APP_PATH . "/Database/MySQL/Connection.php");

use Database\MySQL\Connection;

class LogoutModel
{
    private static $connection;

    public static function logoutUser()
    {
        $token = self::getHeaderToken();

        if ($token) {
            if (self::deactivateToken($token)) {
                Response::setStatus(200);
                Response::setJsonContent(["message" => "Token deactivated"]);
            } else {
                Response::setStatus(200);
                Response::setJsonContent(["error" => "Error deactivating token"]);
            }
        } else {
            Response::setStatus(200);
            Response::setJsonContent(["error" => "No token provided"]);
        }
    }

    private static function getHeaderToken()
    {
        $token = isset($_SERVER['HTTP_AUTHORIZATION']) ? trim($_SERVER['HTTP_AUTHORIZATION']) : '';

        if (empty($token)) {
            return false;
        } else {
            return $token;
        }
    }

    private static function deactivateToken($token)
    {
        $token = str_replace('Bearer ', '', $token);

        try {
            self::$connection = new Connection("localhost", "root", "", "eduRaspored");

            $pdo = self::$connection->getPdo();

            $stmt = $pdo->prepare('UPDATE users SET token_active = 0 WHERE token = :token');
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
