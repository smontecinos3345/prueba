<?php

namespace App;

use Exception;
use ReflectionClass;
use ReflectionParameter;

class Container
{
    private array $factories;

    public function __construct(array $factories = [])
    {
        $this->factories = $factories;
    }

    /**
     * @param class-string $serviceId Service or class name to instantiate
     * @return mixed An instance of the requested service
     * @throws \Exception
     */
    public function get(string $serviceId)
    {
        if (isset($this->factories[$serviceId])) {
            return ($this->factories[$serviceId])($this);
        }

        return $this->instantiate($serviceId);
    }

    private function instantiate(string $class)
    {
        $refl = new ReflectionClass($class);
    
        if ($class === get_class($this)) {
            return $this;
        }
    
        if (!$constructor = $refl->getConstructor()) {
            return new $class();
        }

        $parameters = $this->resolveParameters($constructor->getParameters());

        return $refl->newInstanceArgs($parameters);
    }

    private function resolveParameters(array $parameters): array
    {
        return array_map([$this, 'resolveParameter'], $parameters);
    }

    private function resolveParameter(ReflectionParameter $parameter)
    {
        return $this->resolveDependency($parameter);
    }

    private function resolveDependency(ReflectionParameter $parameter)
    {
        $type = $parameter->getType();

        if (!$type || $type->isBuiltin()) {
            throw new \Exception("Cannot resolve dependency: {$type}");
        }

        return $this->get($type->getName());
    }
}
