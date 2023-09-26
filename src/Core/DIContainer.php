<?php

namespace App\Core;

use App\Contracts\Controller;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class DIContainer implements ContainerInterface
{
    private array $binds = [];
    private array $instances = [];

    /**
     * @throws Exception
     */
    public function get($id)
    {
        //use Singleton and break query builder
        //if (!isset($this->instances[$id])) {
            //$this->instances[$id] = $this->createInstance($id);
        //}
        return $this->createInstance($id);
    }

    public function has($id): bool
    {
        return isset($this->binds[$id]) || class_exists($id);
    }

    public function bind(string $type, string $subtype): void
    {
        $this->binds[$type] = $subtype;
    }

    private function createInstance(string $classname)
    {
        if (isset($this->binds[$classname])) {
            $classname = $this->binds[$classname];
        } elseif (!class_exists($classname)) {
            throw new Exception("No entry found for $classname.");
        }

        $constructorParameters = $this->getConstructorParameters($classname);
        return new $classname(...$constructorParameters);
    }

    private function getConstructorParameters(string $classname): array
    {
        $reflection = new ReflectionClass($classname);
        $constructor = $reflection->getConstructor();

        if (is_null($constructor)) {
            return [];
        }

        return array_map(function ($param) {
            $type = $param->getType();
            if ($type && !$type->isBuiltin()) {
                $dependencyName = $type->getName();
                return $this->get($dependencyName);
            }

            if ($param->isDefaultValueAvailable()) {
                return $param->getDefaultValue();
            }

            throw new \Exception("Cannot resolve the parameter: {$param->getName()}");
        }, $constructor->getParameters());
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function resolveMethodDependencies(Controller $controller, $method, array $getParams): array
    {
        $reflectionMethod = new ReflectionMethod($controller, $method);
        $params = $reflectionMethod->getParameters();

        foreach ($getParams as $key => $param) {
            $params[$key] = $param;
        }

        return array_map(function ($param) {
            $type = null;
            if (is_object($param)) {
                $type = $param->getType();
            }
            if ($type && !$type->isBuiltin()) {
                $dependencyName = $type->getName();
                return $this->get($dependencyName);
            }

            if (is_object($param) && $param->isDefaultValueAvailable()) {
                return $param->getDefaultValue();
            }
            return $param;
        }, $params);
    }
}


