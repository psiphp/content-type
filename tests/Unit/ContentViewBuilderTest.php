<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psi\Component\ContentType\Tests\Unit;

use Metadata\MetadataFactory;
use Metadata\NullMetadata;
use Prophecy\Argument;
use Psi\Component\ContentType\ContentView;
use Psi\Component\ContentType\ContentViewBuilder;
use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\FieldRegistry;
use Psi\Component\ContentType\Metadata\ClassMetadata;
use Psi\Component\ContentType\Metadata\PropertyMetadata;
use Psi\Component\ContentType\ViewInterface;
use Psi\Component\ContentType\ViewRegistry;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentViewBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $builder;
    private $metadataFactory;
    private $viewRegistry;
    private $fieldRegistry;

    public function setUp()
    {
        $this->metadataFactory = $this->prophesize(MetadataFactory::class);
        $this->fieldRegistry = $this->prophesize(FieldRegistry::class);
        $this->viewRegistry = $this->prophesize(ViewRegistry::class);

        $this->builder = new ContentViewBuilder(
            $this->metadataFactory->reveal(),
            $this->fieldRegistry->reveal(),
            $this->viewRegistry->reveal()
        );

        $this->classMetadata = $this->prophesize(ClassMetadata::class);
        $this->propertyMetadata1 = $this->prophesize(PropertyMetadata::class);
        $this->field1 = $this->prophesize(FieldInterface::class);
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

        $this->fieldRegistry->get('foobar')->willReturn($this->field1->reveal());
        $this->propertyMetadata1->getType()->willReturn('foobar');
        $this->propertyMetadata1->getName()->willReturn('prop1');
        $this->field1->getViewType()->willReturn('Some\Type');

        $this->viewRegistry->get('Some\Type')->willReturn($this->viewType->reveal());
        $this->field1->configureOptions(Argument::type(OptionsResolver::class))->will(function ($args) {
            $resolver = $args[0];
            $resolver->setDefault('foo', 'bar');
        });

        $this->propertyMetadata1->getOptions()->willReturn(['foo' => 'baz']);
        $this->propertyMetadata1->getValue($content)->willReturn('value');

        $this->viewType->buildView($this->builder, Argument::type(ContentView::class), $value, ['foo' => 'baz'])->will(function ($args) {
            $view = $args[1];
            $view->setValue('Hello World');
            $view['paginator'] = 'I paginator';
        });

        $view = $this->builder->build($content);

        $this->assertInstanceOf(ContentView::class, $view);
        $this->assertArrayHasKey('prop1', $view);
        $this->assertEquals('Hello World', (string) $view['prop1']);
        $this->assertEquals('I paginator', $view['prop1']['paginator']);
    }
}
