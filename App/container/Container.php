<?php

namespace App\container;

use App\Exceptions\Container\NotFoundException;

class Container
{
    /**
     * @var array<string, mixed> An array of services registered in the container.
     */
    private array $services;

    /**
     * Creates a new instance of the container.
     * @param array<string, mixed>|null $services
     */
    public function __construct(array $services = null)
    {
        if (!is_null($services)) {
            $this->add($services);
        }
    }

    /**
     * @param string $id The ID of the service.
     * @param mixed $service The service to register.
     * @return void
     */
    public function set(string $id, mixed $service) : void
    {
        $this->services[$id] = $service;
    }

    /**
     * Registers multiple services in the container.
     * @param array<string, mixed> $services An array of services to register.
     * @return void
     */
    public function add(array $services) : void
    {
        $this->services += $services;
    }

    /**
     * Gets a service from the container.
     * @param string $id The ID of the service.
     * @return mixed The registered service.
     * @throws NotFoundException if the service is not registered in the container.
     */
    public function get(string $id): mixed
    {
        if (!isset($this->services[$id])) {
            throw new NotFoundException();
        }

        return $this->services[$id];
    }

    /**
     * Checks if a service is registered in the container.
     * @param string $id The ID of the service.
     * @return bool True if the service is registered, false otherwise.
     */
    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }
}