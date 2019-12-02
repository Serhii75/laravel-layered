<?php

namespace SiDev\LaravelLayered\Generic;

use SiDev\LaravelLayered\Contracts\RepositoryInterface;

class ObjectManager
{
    /**
     * Get class by class name.
     *
     * @param string $className
     *
     * @return mixed
     */
    public function getClass(string $className)
    {
        return resolve($className);
    }

    /**
     * Get repository by class name.
     *
     * @param string $className
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function getRepository(string $className)
    {
        $class = resolve($className);

        if ($class instanceof RepositoryInterface) {
            return $class;
        }

        throw new \Exception('Given class '.$className.' is not a repository.');
    }
}
