<?php

namespace Psi\Component\ContentType\Tests\Unit\Standard\View;

use Psi\Component\ContentType\Standard\View\NullType;

class NullTypeTest extends TypeTestCase
{
    public function getType()
    {
        return new NullType();
    }

    /**
     * It should not do anything.
     */
    public function testNoTemplate()
    {
        $this->getType()->createView($this->factory->reveal(), 'nothing', []);
    }
}
