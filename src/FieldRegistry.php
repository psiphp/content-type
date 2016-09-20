<?php

namespace Psi\Component\ContentType;

use Sylius\Component\Registry\ServiceRegistry;

/**
 * Registry for all field types.
 */
class FieldRegistry extends ServiceRegistry
{
    public function __construct()
    {
        parent::__construct(
            FieldInterface::class,
            'content field'
        );
    }
}
