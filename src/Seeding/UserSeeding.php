<?php

namespace App\Seeding;

use Faker\Factory as FakerFactory;

class UserSeeding extends AbstractSeeding
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

            $this->query()
                ->table($this->table)
                ->insert(['email' => $email, 'name' => $name, 'password' => $hashedPassword])
                ->get();
        }
    }
}
