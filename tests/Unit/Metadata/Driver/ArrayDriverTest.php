<?php

namespace Symfony\Cmf\Component\ContentType\Tests\Unit\Metadata\Driver;

use Symfony\Cmf\Component\ContentType\Metadata\Driver\ArrayDriver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Cmf\Component\ContentType\Metadata\ClassMetadata;

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
                'driver' => 'doctrine_orm',
                'fields'=> [
                    'title' => [
                        'type' => 'Class\Fqn\TextField',
                        'view_options' => [
                            'option_1' => 100,
                        ],
                        'form_options' => [
                            'required' => true,
                            'length' => 100,
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
        $this->assertEquals('doctrine_orm', $classMetadata->getDriver());

        $properties = $classMetadata->getPropertyMetadata();
        $this->assertCount(2, $properties);

        $property1 = current($properties);
        $this->assertEquals('Class\Fqn\TextField', $property1->getType());
        $this->assertEquals([ 'option_1' => 100 ], $property1->getViewOptions());
        $this->assertEquals([ 'length' => 100, 'required' => true ], $property1->getFormOptions());

        $property2 = next($properties);
        $this->assertEquals('Class\Fqn\ImageField', $property2->getType());
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
