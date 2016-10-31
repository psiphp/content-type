<?php

namespace Psi\Component\ContentType\Tests\Unit;

use Psi\Component\ContentType\Field;
use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\FieldLoader;
use Psi\Component\ContentType\FieldOptions;
use Psi\Component\ContentType\FieldRegistry;
use Psi\Component\ContentType\Metadata\PropertyMetadata;

class FieldLoaderTest extends \PHPUnit_Framework_TestCase
{
    private $loader;

    public function setUp()
    {
        $this->fieldRegistry = $this->prophesize(FieldRegistry::class);
        $this->loader = new FieldLoader($this->fieldRegistry->reveal());

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

        $field = $this->loader->load('foobar', FieldOptions::create([]));

        $this->assertInstanceOf(Field::class, $field);
    }

    /**
     * It should cache fields.
     */
    public function testCache()
    {
        $this->fieldRegistry->get('foobar')->shouldBeCalledTimes(2)->willReturn($this->field1);
        $this->fieldRegistry->get('barfoo')->shouldBeCalledTimes(1)->willReturn($this->field1);

        $field1 = $this->loader->load('foobar', FieldOptions::create([]));
        $field2 = $this->loader->load('barfoo', FieldOptions::create([]));
        $field3 = $this->loader->load('foobar', FieldOptions::create([]));
        $field4 = $this->loader->load('foobar', FieldOptions::create(['shared' => ['fo' => 'ba']]));

        $this->assertSame($field1, $field3);
        $this->assertNotSame($field2, $field1);
        $this->assertNotSame($field4, $field3);
    }
}
