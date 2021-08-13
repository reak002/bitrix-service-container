<?php

namespace BitrixServiceContainer;

interface ServiceContainerInterface
{
    public function getName(): string;

    public function getClass();
}
