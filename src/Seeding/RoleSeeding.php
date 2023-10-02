<?php

namespace App\Seeding;

class RoleSeeding extends AbstractSeeding
{
    public string $table = 'role';

    public array $data = ['USER', 'ADMIN'];

    public function seed(): void
    {
        foreach ($this->data as $role) {
            $this->query()
                ->table($this->table)
                ->insert(['role' => $role])
                ->get();
        }
    }
}