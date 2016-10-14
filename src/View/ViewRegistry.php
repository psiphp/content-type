<?php

namespace Psi\Component\ContentType\View;

use Sylius\Component\Registry\ServiceRegistry;

class ViewRegistry extends ServiceRegistry
{
    public function __construct()
    {
        parent::__construct(
            ViewInterface::class,
            'view'
        );
    }
}
