<?php

namespace App\Seeding;


class DeliverySeeding extends AbstractSeeding
{
    public string $table = 'delivery';

    public array $data = ['Postal' => 100, 'Courier' => 150];
    public function seed(): void
    {
        foreach ($this->data as $key => $value) {

            $this->query()
                ->table($this->table)
                ->insert(['delivery_type' => $key, 'price' => $value])
                ->get();
        }
    }
}