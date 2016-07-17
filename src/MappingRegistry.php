<?php

namespace Symfony\Cmf\Component\ContentType;

use Sylius\Component\Registry\ServiceRegistry;
use Symfony\Cmf\Component\ContentType\MappingInterface;

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
