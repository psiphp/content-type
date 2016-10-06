<?php

namespace Psi\Component\ContentType\Tests\Unit\Form\Extension;

use Metadata\MetadataFactoryInterface;
use Psi\Component\ContentType\FieldRegistry;
use Psi\Component\ContentType\Form\Extension\FieldExtension;
use Psi\Component\ContentType\Form\Extension\Type\SurrogateType;
use Psi\Component\ContentType\Metadata\ClassMetadata;

class FieldExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $metadataFactory;
    private $fieldRegistry;
    private $extension;

    public function setUp()
    {
        $this->metadataFactory = $this->prophesize(MetadataFactoryInterface::class);
        $this->fieldRegistry = $this->prophesize(FieldRegistry::class);

        $this->extension = new FieldExtension(
            $this->metadataFactory->reveal(),
            $this->fieldRegistry->reveal()
        );

        $this->metadata = $this->prophesize(ClassMetadata::class);
    }

    /**
     * It should return true if it hasType is passed an object registered with the metadata factory.
     */
    public function testHasTypeRegisteredMetadataFactory()
    {
        $this->metadataFactory->getMetadataForClass(\stdClass::class)
            ->willReturn($this->metadata->reveal());

        $result = $this->extension->hasType(\stdClass::class);

        $this->assertTrue($result);
    }

    /**
     * hasType should return false if an there is no metadata nor built-in type.
     */
    public function testHasNoType()
    {
        $result = $this->extension->hasType('foobar');

        $this->assertFalse($result);
    }

    /**
     * It should getType for a content entity.
     */
    public function testGetTypeContent()
    {
        $this->metadataFactory->getMetadataForClass(\stdClass::class)
            ->willReturn($this->metadata->reveal());

        $result = $this->extension->getType(\stdClass::class);

        $this->assertInstanceOf(SurrogateType::class, $result);
    }

    /**
     * It should throw an exception if an unknown type is requested from getType.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage cannot be loaded
     */
    public function testGetTypeUnknown()
    {
        $this->metadataFactory->getMetadataForClass('foobar')->willReturn(null);

        $this->extension->getType('foobar');
    }
}
