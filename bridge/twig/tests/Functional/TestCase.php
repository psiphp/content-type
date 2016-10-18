<?php

namespace Psi\Bridge\ContentType\Twig\Tests\Functional;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function getContainer(array $config)
    {
        $container = new Container($config);

        return $container;
    }
}
