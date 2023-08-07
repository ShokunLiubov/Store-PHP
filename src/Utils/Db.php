<?php

namespace App\Utils;

use PDO;
use PDOStatement;

class DataBase
{
    public static $db;
    public static function dbConnect(): PDO
    {

        if (self::$db === null) {
            self::$db = new PDO('mysql:host=localhost;dbname=makeUpStore', 'root', '', [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // для адекватного выведения данных из db
            ]);

            self::$db->exec('SET NAMES UTF8');
        }

        return self::$db;
    }

    public function dbQuery(string $sql, array $params = []): PDOStatement
    {
        self::dbConnect();
        $query = self::$db->prepare($sql);
        $query->execute($params);

        $errorInfo = $query->errorInfo();

        if ($errorInfo[0] != PDO::ERR_NONE) {
            echo $errorInfo[2];
            exit();
        }

        return $query;
    }
}

// function dbConnect(): PDO
// {
//     static $db;

//     if ($db === null) {
//         $db = new PDO('mysql:host=localhost;dbname=makeUpStore', 'root', '', [
//             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // для адекватного выведения данных из db
//         ]);

//         $db->exec('SET NAMES UTF8');
//     }

//     return $db;
// }

// function dbQuery(string $sql, array $params = []): PDOStatement
// {
//     $db = dbConnect();
//     $query = $db->prepare($sql);
//     $query->execute($params);

//     $errorInfo = $query->errorInfo();

//     if ($errorInfo[0] != PDO::ERR_NONE) {
//         echo $errorInfo[2];
//         exit();
//     }

//     return $query;
// }