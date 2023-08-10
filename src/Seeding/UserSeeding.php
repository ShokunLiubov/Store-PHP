<?php

namespace App\Seeding;

use App\Utils\DataBase;
use App\Seeding\AbstractSeed;
use Faker\Factory as FakerFactory;
use Exception;

class UserSeeding extends AbstractSeed
{
    public string $table = 'user';

    public function seed()
    {
        try {
            $faker = FakerFactory::create();
            $db = new DataBase();
            for ($i = 1; $i <= $this->count; $i++) {
                $email = $faker->email();
                $name = $faker->name();
                $password = $faker->password;
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $sql = "INSERT INTO " . $this->getTableName() . " (`email`, `name`, `password`)
                VALUES (?, ?, ?)";
                $db->dbQuery($sql, [$email, $name, $hashedPassword]);
            }
        } catch (Exception $e) {
        }
    }
}
