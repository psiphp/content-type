<?php

namespace Symfony\Cmf\Component\ContentType;

use Sylius\Component\Registry\ServiceRegistry;

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
