<?php

namespace Psi\Component\ContentType\Tests\Unit\Standard\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\FieldRegistry;
use Psi\Component\ContentType\Standard\Field\CollectionField;

class CollectionFieldTest extends FieldTestCase
{
    private $registry;

    public function setUp()
    {
        $this->registry = $this->prophesize(FieldRegistry::class);
    }

    protected function getField(): FieldInterface
    {
        return new CollectionField($this->registry->reveal());
    }
}
