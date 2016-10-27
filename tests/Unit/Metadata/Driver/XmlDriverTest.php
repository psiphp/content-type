<?php

namespace Psi\Component\ContentType\Tests\Unit\Metadata\Driver;

use Metadata\Driver\FileLocator;
use Psi\Component\ContentType\Metadata\ClassMetadata;
use Psi\Component\ContentType\Metadata\Driver\XmlDriver;
use Psi\Component\ContentType\Tests\Unit\Metadata\Driver\Model\Example;
use Psi\Component\ContentType\Tests\Unit\Metadata\Driver\Model\InvalidExample;

class XmlDriverTest extends \PHPUnit_Framework_TestCase
{
    private $driver;

    public function setUp()
    {
        $this->driver = new XmlDriver(new FileLocator([
            'Psi\\Component\\ContentType\\Tests\\Unit\\Metadata\\Driver\\Model' => __DIR__ . '/xml',
        ]));
    }

    /**
     * It should load metadata.
     */
    public function testLoadMetadata()
    {
        $metadata = $this->driver->loadMetadataForClass(new \ReflectionClass(Example::class));
        $this->assertInstanceOf(ClassMetadata::class, $metadata);
        $properties = $metadata->getPropertyMetadata();
        $this->assertArrayHasKey('fieldOne', $properties);
        $propertyOne = $properties['fieldOne'];
        $this->assertEquals('markdown', $propertyOne->getType());
        $this->assertEquals('title', $propertyOne->getRole());
        $this->assertEquals('group_one', $propertyOne->getGroup());
        $this->assertEquals([
            'option_one' => 'Foobar',
            'option_two' => 'Barfoo',
            'option_three' => [
                'sub_one' => 'One',
                'sub_two' => 'Two',
            ],
        ], $propertyOne->getOptions());
        $this->assertArrayHasKey('fieldTwo', $properties);
    }

    /**
     * It should validate the XML with the schema.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Could not validate XML mapping at
     */
    public function testValidation()
    {
        $this->driver->loadMetadataForClass(new \ReflectionClass(InvalidExample::class));
    }
}
