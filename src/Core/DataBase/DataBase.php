<?php

namespace App\Core\DataBase;

use PDO;
use PDOStatement;

class DataBase
{
    public static $db;
    public static function dbConnect(): PDO
    {

        if (self::$db === null) {
            self::$db = new PDO('mysql:host=' . DB_HOST .';dbname=' . DB_NAME, DB_USER, DB_PASS, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            self::$db->exec('SET NAMES UTF8');
        }

        return self::$db;
    }

    public function dbQuery(string $sql, array $params = [], array $types = []): PDOStatement
    {
        self::dbConnect();
        $query = self::$db->prepare($sql);

        foreach ($params as $key => $value) {
            if (isset($types[$key])) {
                $query->bindValue($key, $value, $types[$key]);
            } else {
                $query->bindValue($key, $value);
            }
        }

        $query->execute();

        $errorInfo = $query->errorInfo();

        if ($errorInfo[0] != PDO::ERR_NONE) {
            response()->view('Errors/Error', ['error' => $errorInfo[2]]);
            exit();
        }

        return $query;
    }

    public function insertAndGetId(string $sql, array $params = [], array $types = []): int
    {
        $this->dbQuery($sql, $params, $types);

        return (int)self::$db->lastInsertId();
    }
}
