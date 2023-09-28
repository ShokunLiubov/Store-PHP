<?php

namespace App\Seeding;

use Exception;
use Faker\Factory as FakerFactory;

class UserSeeding extends AbstractSeed
{
    public string $table = 'user';

    public function seed(): void
    {
        $faker = FakerFactory::create();
        for ($i = 1; $i <= $this->count; $i++) {
            $email = $faker->email();
            $name = $faker->name();
            $password = $faker->password;
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO " . $this->getTableName() . " (`email`, `name`, `password`)
                    VALUES (:email, :name, :password)";
            db()->dbQuery($sql, ['email' => $email, 'name' => $name, 'password' => $hashedPassword]);
        }
    }
}
