<?php

namespace BitrixServiceContainer;

abstract class AbstractServiceContainer implements ServiceContainerInterface
{
    protected $name;

    /** @var EnvService */
    protected $envService;

    public function __construct(EnvService $envService)
    {
        $this->envService = $envService;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
