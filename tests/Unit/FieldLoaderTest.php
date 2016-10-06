<?php

namespace Psi\Component\ContentType\Tests\Unit;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\FieldLoader;
use Psi\Component\ContentType\FieldRegistry;
use Psi\Component\ContentType\LoadedField;
use Psi\Component\ContentType\Metadata\PropertyMetadata;
use Psi\Component\ContentType\Storage\Mapping\TypeFactory;

class FieldLoaderTest extends \PHPUnit_Framework_TestCase
{
    private $loader;

    public function setUp()
    {
        $this->typeFactory = $this->prophesize(TypeFactory::class);
        $this->fieldRegistry = $this->prophesize(FieldRegistry::class);
        $this->loader = new FieldLoader($this->typeFactory->reveal(), $this->fieldRegistry->reveal());

        $this->property1 = $this->prophesize(PropertyMetadata::class);
        $this->property2 = $this->prophesize(PropertyMetadata::class);

        $this->field1 = $this->prophesize(FieldInterface::class);
        $this->field2 = $this->prophesize(FieldInterface::class);
    }

    /**
     * It should return a loaded field for the given property metadata.
     */
    public function testReturnLoaded()
    {
        $this->property1->getType()->willReturn('foobar');
        $this->property1->getOptions()->willReturn([]);
        $this->fieldRegistry->get('foobar')->willReturn($this->field1);
        $field = $this->loader->loadForProperty($this->property1->reveal());

        $this->assertInstanceOf(LoadedField::class, $field);
        $this->assertSame($this->field1->reveal(), $field->getInnerField());
    }

    /**
     * It should cache fields.
     */
    public function testCache()
    {
        $this->property1->getType()->willReturn('foobar');
        $this->property2->getType()->willReturn('barfoo');
        $this->property1->getOptions()->willReturn([]);
        $this->property2->getOptions()->willReturn([]);

        $this->fieldRegistry->get('foobar')->shouldBeCalledTimes(1)->willReturn($this->field1);
        $this->fieldRegistry->get('barfoo')->shouldBeCalledTimes(1)->willReturn($this->field1);

        $field1 = $this->loader->loadForProperty($this->property1->reveal());
        $field2 = $this->loader->loadForProperty($this->property2->reveal());
        $field3 = $this->loader->loadForProperty($this->property1->reveal());

        $this->assertSame($field1, $field3);
        $this->assertNotSame($field2, $field1);
    }
}
