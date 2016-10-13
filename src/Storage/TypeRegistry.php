<?php

namespace Psi\Component\ContentType\Storage;

use Sylius\Component\Registry\ServiceRegistry;

/**
 * Registry for mapping objects.
 */
class TypeRegistry extends ServiceRegistry
{
    public function __construct()
    {
        parent::__construct(
            TypeInterface::class,
            'storage type'
        );
    }
}
