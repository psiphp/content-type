<?php

namespace Psi\Component\ContentType\Tests\Unit\View;

use Metadata\MetadataFactory;
use Metadata\NullMetadata;
use Prophecy\Argument;
use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\FieldLoader;
use Psi\Component\ContentType\LoadedField;
use Psi\Component\ContentType\Metadata\ClassMetadata;
use Psi\Component\ContentType\Metadata\PropertyMetadata;
use Psi\Component\ContentType\View\View;
use Psi\Component\ContentType\View\ViewBuilder;
use Psi\Component\ContentType\View\ViewInterface;
use Psi\Component\ContentType\View\ViewRegistry;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ViewBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $builder;
    private $metadataFactory;
    private $viewRegistry;
    private $fieldLoader;

    public function setUp()
    {
        $this->metadataFactory = $this->prophesize(MetadataFactory::class);
        $this->fieldLoader = $this->prophesize(FieldLoader::class);
        $this->viewRegistry = $this->prophesize(ViewRegistry::class);

        $this->builder = new ViewBuilder(
            $this->metadataFactory->reveal(),
            $this->fieldLoader->reveal(),
            $this->viewRegistry->reveal()
        );

        $this->classMetadata = $this->prophesize(ClassMetadata::class);
        $this->propertyMetadata1 = $this->prophesize(PropertyMetadata::class);
        $this->field1 = $this->prophesize(FieldInterface::class);
        $this->loadedField1 = $this->prophesize(LoadedField::class);
        $this->viewType = $this->prophesize(ViewInterface::class);
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

        $this->builder->build(new \stdClass());
    }

    /**
     * If should build the view.
     */
    public function testBuildView()
    {
        $content = new \stdClass();
        $value = 'value';

        $this->metadataFactory->getMetadataForClass('stdClass')->willReturn(
            $this->classMetadata->reveal()
        );
        $this->classMetadata->getPropertyMetadata()->willReturn([
            $this->propertyMetadata1->reveal(),
        ]);

        $this->fieldLoader->load('foobar', ['foo' => 'baz'])->willReturn($this->loadedField1->reveal());
        $this->propertyMetadata1->getType()->willReturn('foobar');
        $this->propertyMetadata1->getName()->willReturn('prop1');
        $this->loadedField1->getInnerField()->willReturn($this->field1);
        $this->field1->getViewType()->willReturn('Some\Type');
        $this->loadedField1->getViewOptions()->willReturn([
            'foo' => 'baz',
        ]);

        $this->viewRegistry->get('Some\Type')->willReturn($this->viewType->reveal());

        $this->propertyMetadata1->getOptions()->willReturn(['foo' => 'baz']);
        $this->propertyMetadata1->getValue($content)->willReturn('value');

        $this->viewType->buildView(Argument::type(View::class), $value, ['foo' => 'baz', 'template' => null])->will(function ($args) {
            $view = $args[0];
            $view->setValue('Hello World');
            $view['paginator'] = 'I paginator';
        });
        $this->viewType->configureOptions(Argument::type(OptionsResolver::class))->will(function ($args) {
            $args[0]->setDefault('foo', 'barrrr');
        });

        $view = $this->builder->build($content);

        $this->assertInstanceOf(View::class, $view);
        $this->assertArrayHasKey('prop1', $view);
        $this->assertEquals('Hello World', (string) $view['prop1']);
        $this->assertEquals('I paginator', $view['prop1']['paginator']);
    }
}
