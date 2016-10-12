<?php

namespace Psi\Component\ContentType\Tests\Unit\Metadata\Driver;

use Psi\Component\ContentType\Metadata\ClassMetadata;
use Psi\Component\ContentType\Metadata\Driver\ArrayDriver;

class ArrayDriverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should return NULL if the class is not known.
     */
    public function testClassNotKnown()
    {
        $reflection = new \ReflectionClass('stdClass');
        $driver = $this->createDriver([]);
        $metadata = $driver->loadMetadataForClass($reflection);

        $this->assertNull($metadata);
    }

    /**
     * It should return class metadata.
     */
    public function testLoadClassMetadata()
    {
        $reflection = new \ReflectionClass(TestContent::class);
        $driver = $this->createDriver([
            TestContent::class => [
                'fields' => [
                    'title' => [
                        'type' => 'Class\Fqn\TextField',
                        'group' => 'foobar',
                        'options' => [
                            'option_1' => 100,
                        ],
                    ],
                    'image' => [
                        'type' => 'Class\Fqn\ImageField',
                    ],
                ],
            ],
        ]);

        $classMetadata = $driver->loadMetadataForClass($reflection);
        $this->assertInstanceOf(ClassMetadata::class, $classMetadata);
        $this->assertEquals(TestContent::class, $classMetadata->getName());

        $properties = $classMetadata->getPropertyMetadata();
        $this->assertCount(2, $properties);

        $property1 = current($properties);
        $this->assertEquals('Class\Fqn\TextField', $property1->getType());
        $this->assertEquals(['option_1' => 100], $property1->getOptions());
        $this->assertEquals('foobar', $property1->getGroup());

        $property2 = next($properties);
        $this->assertEquals('Class\Fqn\ImageField', $property2->getType());
    }

    /**
     * If the form is compound, it should add the serialize transformer.
     *
     * NOTE: We will be a strategy in the future to enable things such as Doctrine Embedables.
     */
    public function testCompound()
    {
        $this->markTestIncomplete('TODO');
    }

    /**
     * It should throw an exception if invalid field configuration keys are given.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid configuration key(s) "bar", "foo" for field "title" on class "Psi\Component\ContentType\Tests\Unit\Metadata\Driver\TestContent", valid keys: "type", "role", "group", "options"
     */
    public function testInvalidKeys()
    {
        $reflection = new \ReflectionClass(TestContent::class);
        $driver = $this->createDriver([
            TestContent::class => [
                'fields' => [
                    'title' => [
                        'bar' => 'boo',
                        'foo' => 'baa',
                        'type' => 'Class\Fqn\TextField',
                        'options' => [
                            'option_1' => 100,
                        ],
                    ],
                    'image' => [
                        'type' => 'Class\Fqn\ImageField',
                    ],
                ],
            ],
        ]);
        $driver->loadMetadataForClass($reflection);
    }

    private function createDriver(array $config)
    {
        return new ArrayDriver($config);
    }
}

class TestContent
{
    private $title;
    private $image;
}
