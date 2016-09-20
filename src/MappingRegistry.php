<?php

namespace Psi\Component\ContentType;

use Sylius\Component\Registry\ServiceRegistry;

/**
 * Registry for mapping objects.
 */
class MappingRegistry extends ServiceRegistry
{
    public function __construct()
    {
        parent::__construct(
            MappingInterface::class,
            'mapping'
        );
    }
}
