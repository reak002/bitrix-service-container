<?php

namespace BitrixServiceContainer;

use BitrixClassLoaderHelper\ClassLoaderHelper;

class ServiceContainer
{
    /** @var ClassLoaderHelper */
    protected $classLoaderHelper;

    protected $services = [];

    public function addService(ServiceContainerInterface $service): void
    {
        $this->services[] = $service;
    }

    public function register(string $directory): void
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));
        $allFiles = array_filter(iterator_to_array($iterator), function ($file) {
            return $file->isFile();
        });

        foreach ($allFiles as $file) {
            $class = $this->classLoaderHelper->getClassFullNameFromFile($file);

            try {
                $reflectionClass = new \ReflectionClass($class);
                if (!$reflectionClass->isAbstract() && is_a($class, ServiceContainerInterface::class, true)) {
                    require_once $file;
                    $this->addService(new $class(new EnvService()));
                }
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    public function __construct(string $directory)
    {
        $this->classLoaderHelper = new ClassLoaderHelper();
        $this->register($directory);
    }

    public function get($name)
    {
        /** @var AbstractServiceContainer $service */
        foreach ($this->services as $service) {
            if ($service->getName() == $name) {
                return $service->getClass();
            }
        }
        return null;
    }
}
