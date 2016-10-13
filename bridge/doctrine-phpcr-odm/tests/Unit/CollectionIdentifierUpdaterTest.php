<?php

namespace Psi\Bridge\ContentType\Tests\Unit\Doctrine\PhpcrOdm;

use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Doctrine\ODM\PHPCR\Mapping\ClassMetadata as OdmMetadata;
use Doctrine\ODM\PHPCR\Mapping\ClassMetadataFactory;
use Metadata\MetadataFactoryInterface;
use Psi\Bridge\ContentType\Doctrine\PhpcrOdm\CollectionIdentifierUpdater;
use Psi\Bridge\ContentType\Doctrine\PhpcrOdm\PropertyEncoder;
use Psi\Bridge\ContentType\Metadata\ClassMetadata;

class CollectionIdentifierUpdaterTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->metadataFactory = $this->prophesize(MetadataFactoryInterface::class);
        $this->encoder = $this->prophesize(PropertyEncoder::class);

        $this->updater = new CollectionIdentifierUpdater(
            $this->metadataFactory->reveal(),
            $this->encoder->reveal()
        );

        $this->documentManager = $this->prophesize(DocumentManagerInterface::class);
        $this->document = new \stdClass();
        $this->odmMetadataFactory = $this->prophesize(ClassMetadataFactory::class);
        $this->odmMetadata = $this->prophesize(OdmMetadata::class);
        $this->ctMetadata = $this->prophesize(ClassMetadata::class);

        $this->documentManager->getMetadataFactory()->willReturn(
            $this->odmMetadataFactory->reveal()
        );

        $this->document = new \stdClass();
        $this->child1 = new \stdClass();
        $this->child2 = new \stdClass();
    }

    /**
     * It should return early if there is CT system has no metadata.
     */
    public function testReturnEarlyNoCtMetadata()
    {
        $this->odmMetadataFactory->getMetadataFor(\stdClass::class)->willReturn(
            $this->odmMetadata->reveal()
        );
        $this->metadataFactory->getMetadataForClass(\stdClass::class)->willReturn(null);
        $this->updater->update($this->documentManager->reveal(), $this->document);
        $this->odmMetadata->getIdentifierValue($this->document)->shouldNotHaveBeenCalled();
    }

    /**
     * It should ignore children ODM mappings that have no corresponding CT mapping.
     */
    public function testIgnoreNonCtMappings()
    {
        $identifier = '/path/to/document';
        $this->odmMetadataFactory->getMetadataFor(\stdClass::class)->willReturn(
            $this->odmMetadata->reveal()
        );
        $this->metadataFactory->getMetadataForClass(\stdClass::class)->willReturn($this->ctMetadata->reveal());

        $this->odmMetadata->getIdentifierValue($this->document)->willReturn($identifier);
        $this->odmMetadata->childrenMappings = [
            'some_collection',
        ];

        $this->odmMetadata->getFieldValue($this->document, 'some_collection')->shouldNotBeCalled();
        $this->ctMetadata->propertyMetadata = [];

        $this->updater->update($this->documentManager->reveal(), $this->document);
        $this->odmMetadata->getFieldValue($this->document, 'some_collection')->shouldNotHaveBeenCalled();
    }

    /**
     * It should set the identifier value of collection children documents.
     */
    public function testSetIdentifierValue()
    {
        $identifier = '/path/to/document';
        $this->odmMetadataFactory->getMetadataFor(\stdClass::class)->willReturn(
            $this->odmMetadata->reveal()
        );
        $this->metadataFactory->getMetadataForClass(\stdClass::class)->willReturn($this->ctMetadata->reveal());

        $this->odmMetadata->getIdentifierValue($this->document)->willReturn($identifier);
        $this->odmMetadata->childrenMappings = [
            'some_collection',
        ];
        $this->ctMetadata->propertyMetadata = [
            'some_collection' => 'something',
        ];
        $this->odmMetadata->getFieldValue($this->document, 'some_collection')->willReturn([
            $this->child1,
            $this->child2,
        ]);

        $this->encoder->encode('some_collection', 0)->willReturn('some_collection-0');
        $this->encoder->encode('some_collection', 1)->willReturn('some_collection-1');
        $this->odmMetadata->setIdentifierValue($this->child1, $identifier . '/some_collection-0')->shouldBeCalled();
        $this->odmMetadata->setIdentifierValue($this->child2, $identifier . '/some_collection-1')->shouldBeCalled();

        $this->updater->update($this->documentManager->reveal(), $this->document);
    }
}
