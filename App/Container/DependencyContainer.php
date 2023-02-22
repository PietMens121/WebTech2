<?php

namespace App\Container;

use App\Exceptions\Container\NotFoundException;
use Psr\Container\ContainerInterface;

class DependencyContainer implements ContainerInterface
{
    private array $dependencies = [];

    /**
     * @param array $dependencies
     */
    public function __construct(array $dependencies)
    {
        $this->dependencies = $dependencies;
    }

    /**
     * @param string $id
     * @return mixed|void
     * @throws NotFoundException
     */
    public function get(string $id)
    {
        if ($this->has($id)) {
            return $this->dependencies[$id];
        } else {
            throw new NotFoundException("this dependency does not exist");
        }
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        if (in_array($id, $this->dependencies)){
            return true;
        } else
            return false;
    }
}