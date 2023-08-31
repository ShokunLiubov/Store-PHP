<?php

namespace App\Core;

class DIContainer
{
    private array $definitions = [];
    private array $instances = [];

    public function set(string $name, callable $factory): void
    {
        $this->definitions[$name] = $factory;
    }

    public function get(string $name)
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        if (isset($this->definitions[$name])) {
            $this->instances[$name] = $this->definitions[$name]();
            return $this->instances[$name];
        }

        throw new \Exception("Service not found: " . $name);
    }
}

