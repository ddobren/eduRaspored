<?php

require_once(VN_APP_PATH . "/Database/MySQL/Connection.php");

use Database\MySQL\Connection;

class ScheduleModel
{
    private static $connection;

    public static function addNewSchedule($school_id, $name, $place, $class_info, $schedule, $user_token)
    {
        try {
            self::$connection = new Connection("localhost", "root", "", "eduRaspored");
            $pdo = self::$connection->getPdo();

            $name = filter_var($name, FILTER_SANITIZE_STRING);
            $place = filter_var($place, FILTER_SANITIZE_STRING);
            $class_info = filter_var($class_info, FILTER_SANITIZE_STRING);

            $class_info_lowercase = strtolower($class_info);

            if (empty($name) || empty($place) || empty($class_info) || empty($schedule) || empty($user_token)) {
                self::respondWithError("Svi podaci moraju biti ispunjeni.");
                return;
            }

            if (self::scheduleExists($pdo, $name, $class_info_lowercase)) {
                self::respondWithError("Raspored je već dodan za taj razred!");
                return;
            }

            $addedScheduleId = self::insertNewSchedule($pdo, $school_id, $name, $place, $class_info, $schedule, $user_token);

            if ($addedScheduleId !== false) {
                Response::setStatus(200);
                exit();
            } else {
                self::respondWithError("Greška prilikom dodavanja rasporeda.");
            }
        } catch (\PDOException $e) {
            self::respondWithError("Error adding schedule: " . $e->getMessage());
        }
    }

    private static function insertNewSchedule($pdo, $school_id, $name, $place, $class_info, $schedule, $user_token)
    {
        try {
            $stmt = $pdo->prepare('INSERT INTO timetables (school_id, name, place, class_info, schedule, user_token) VALUES (:school_id, :name, :place, :class_info, :schedule, :user_token)');
            $stmt->bindParam(':school_id', $school_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':place', $place);
            $stmt->bindParam(':class_info', $class_info);
            $stmt->bindParam(':schedule', $schedule);
            $stmt->bindParam(':user_token', $user_token);

            $stmt->execute();

            return $pdo->lastInsertId();
        } catch (\PDOException $e) {
            return false;
        }
    }

    private static function scheduleExists($pdo, $name, $class_info)
    {
        try {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM timetables WHERE LOWER(name) = LOWER(:name) AND LOWER(class_info) = LOWER(:class_info)');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':class_info', $class_info);
            $stmt->execute();

            $count = $stmt->fetchColumn();

            return $count > 0;
        } catch (\PDOException $e) {
            self::respondWithError("Error checking name and class info: " . $e->getMessage());
            return false;
        }
    }

    private static function respondWithError($message)
    {
        Response::setStatus(500);
        Response::setJsonContent(["error" => $message]);
        exit();
    }
}
