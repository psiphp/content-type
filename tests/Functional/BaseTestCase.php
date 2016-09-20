<?php

namespace Psi\Component\ContentType\Tests\Functional;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    public function getContainer(array $config = [])
    {
        return new Container($config);
    }
}
