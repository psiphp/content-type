<?php

namespace Psi\Component\ContentType\Tests\Unit;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\FieldLoader;
use Psi\Component\ContentType\FieldRegistry;
use Psi\Component\ContentType\LoadedField;
use Psi\Component\ContentType\Metadata\PropertyMetadata;
use Psi\Component\ContentType\Storage\TypeFactory;

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
        $this->fieldRegistry->get('foobar')->willReturn($this->field1);

        $field = $this->loader->load('foobar', []);

        $this->assertInstanceOf(LoadedField::class, $field);
        $this->assertSame($this->field1->reveal(), $field->getInnerField());
    }

    /**
     * It should cache fields.
     */
    public function testCache()
    {
        $this->fieldRegistry->get('foobar')->shouldBeCalledTimes(2)->willReturn($this->field1);
        $this->fieldRegistry->get('barfoo')->shouldBeCalledTimes(1)->willReturn($this->field1);

        $field1 = $this->loader->load('foobar', []);
        $field2 = $this->loader->load('barfoo', []);
        $field3 = $this->loader->load('foobar', []);
        $field4 = $this->loader->load('foobar', ['fo' => 'ba']);

        $this->assertSame($field1, $field3);
        $this->assertNotSame($field2, $field1);
        $this->assertNotSame($field4, $field3);
    }
}
