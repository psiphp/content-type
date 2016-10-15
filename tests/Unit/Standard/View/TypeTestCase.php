<?php

namespace Psi\Component\ContentType\Tests\Unit\Standard\View;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class TypeTestCase extends \PHPUnit_Framework_TestCase
{
    abstract protected function getType();

    protected function resolveOptions($options = [])
    {
        $resolver = new OptionsResolver();
        $this->getType()->configureOptions($resolver);

        return $resolver->resolve($options);
    }
}
