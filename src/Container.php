<?php

namespace App;

use Exception;
use ReflectionClass;
use ReflectionParameter;

/**
 * A simple dependency injection container for auto-wiring services in the application.
 *
 * This container allows for automatic resolution of dependencies using reflection.
 * It can instantiate classes and resolve their constructor dependencies recursively.
 */
class Container
{
    private array $factories;

    /**
     * @param array<string,callable> Array of factories for registered services.
     * Factories are used to instantiate and associate the correct adapter with its corresponding port.
     */
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

        // If someone is asking for the container, although discouraged, return
        // itself as containers can't instance themselves.
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

        // can't resolve primitive types
        // consumer should use a factory to inject those values
        // possibly reusing the container to get solvable dependencies
        if (!$type || $type->isBuiltin()) {
            throw new \Exception("Cannot resolve dependency: {$type}");
        }

        return $this->get($type->getName());
    }
}
