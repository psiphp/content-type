<?php

namespace Psi\Component\ContentType\Tests\Unit\Standard\View;

use Metadata\MetadataFactory;
use Metadata\NullMetadata;
use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\FieldLoader;
use Psi\Component\ContentType\LoadedField;
use Psi\Component\ContentType\Metadata\ClassMetadata;
use Psi\Component\ContentType\Metadata\PropertyMetadata;
use Psi\Component\ContentType\Standard\View\ObjectType;
use Psi\Component\ContentType\Standard\View\ObjectView;
use Psi\Component\ContentType\View\ViewFactory;
use Psi\Component\ContentType\View\ViewInterface;

class ObjectTypeTest extends TypeTestCase
{
    private $type;
    private $metadataFactory;

    public function setUp()
    {
        $this->metadataFactory = $this->prophesize(MetadataFactory::class);
        $this->fieldLoader = $this->prophesize(FieldLoader::class);
        $this->viewFactory = $this->prophesize(ViewFactory::class);

        $this->classMetadata = $this->prophesize(ClassMetadata::class);
        $this->propertyMetadata1 = $this->prophesize(PropertyMetadata::class);

        $this->childView = $this->prophesize(ViewInterface::class);
        $this->field = $this->prophesize(LoadedField::class);
        $this->innerField = $this->prophesize(FieldInterface::class);
    }

    protected function getType()
    {
        return new ObjectType(
            $this->metadataFactory->reveal(),
            $this->fieldLoader->reveal()
        );
    }

    /**
     * It should throw an exception if NullMetadata is returned.
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Class "stdClass" is not mapped
     */
    public function testNotMapped()
    {
        $this->metadataFactory->getMetadataForClass('stdClass')->willReturn(
            new NullMetadata('stdClass')
        );

        $this->getType()->createView($this->viewFactory->reveal(), new \stdClass(), []);
    }

    /**
     * If should create an object view.
     */
    public function testCreateView()
    {
        $content = new \stdClass();
        $value = 'value';

        $this->metadataFactory->getMetadataForClass('stdClass')->willReturn(
            $this->classMetadata->reveal()
        );
        $this->classMetadata->getPropertyMetadata()->willReturn([
            $this->propertyMetadata1->reveal(),
        ]);

        $options = ['foo' => 'bar'];
        $this->propertyMetadata1->getType()->willReturn('foobar');
        $this->propertyMetadata1->getName()->willReturn('prop1');
        $this->propertyMetadata1->getOptions()->willReturn($options);
        $this->propertyMetadata1->getValue($content)->willReturn($value);

        $this->viewFactory->create('foobar', $value, $options)->willReturn($this->childView->reveal());

        $this->fieldLoader->load('foobar', $options)->willReturn(
            $this->field->reveal()
        );
        $this->field->getInnerField()->willReturn($this->innerField->reveal());
        $this->innerField->getViewType()->willReturn('foobar');
        $this->field->getViewOptions()->willReturn($options);

        $view = $this->getType()->createView(
            $this->viewFactory->reveal(),
            $content,
            [
                'template' => 'adsf',
            ]
        );

        $this->assertInstanceOf(ObjectView::class, $view);
        $view = $view['prop1'];
        $this->assertSame($this->childView->reveal(), $view);
    }
}
