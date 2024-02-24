<?php

use Firebase\JWT\Key;

require_once(VN_APP_PATH . "/Database/MySQL/Connection.php");

use Database\MySQL\Connection;

class SearchScheduleModel
{
    private static $connection;

    private static $schoolInfo;

    public static function searchEvents(int $school_id)
    {
        $scheduleData = self::pullEvents($school_id);

        if ($scheduleData) {
            Response::setStatus(200);
            Response::setJsonContent($scheduleData);
        } else {
            Response::setStatus(404);
            Response::setJsonContent(["error" => "Raspored nije pronađen"]);
        }
    }

    private static function pullEvents(int $school_id)
    {
        try {
            self::$connection = new Connection("localhost", "root", "", "eduRaspored");

            $pdo = self::$connection->getPdo();

            $stmt = $pdo->prepare('SELECT * FROM timetables WHERE school_id = :school_id');

            $stmt->bindParam(':school_id', $school_id);

            $stmt->execute();

            // Dobavi sve podatke o rasporedu ako postoji :)
            $scheduleData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $scheduleData; // Vrati podatke o rasporedu ili prazan niz ako rasporedi ne postoje
        } catch (PDOException $e) {
            Response::setStatus(500);
            Response::setJsonContent(["error" => "Error searching events: " . $e->getMessage()]);
            return null; // Vrati null u slučaju iznimke
        }
    }
}
