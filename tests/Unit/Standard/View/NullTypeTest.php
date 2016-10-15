<?php

namespace Psi\Component\ContentType\Tests\Unit\Standard\View;

use Psi\Component\ContentType\Standard\View\NullType;
use Psi\Component\ContentType\View\ViewFactory;

class NullTypeTest extends TypeTestCase
{
    private $type;

    public function setUp()
    {
        $this->viewFactory = $this->prophesize(ViewFactory::class);
    }

    public function getType()
    {
        return new NullType();
    }

    /**
     * It should not do anything.
     */
    public function testNoTemplate()
    {
        $this->getType()->createView($this->viewFactory->reveal(), 'nothing', []);
    }
}
