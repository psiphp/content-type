<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Component\ContentType\Tests\Unit\Form;

use Metadata\MetadataFactory;
use Metadata\NullMetadata;
use Symfony\Cmf\Component\ContentType\FieldInterface;
use Symfony\Cmf\Component\ContentType\FieldRegistry;
use Symfony\Cmf\Component\ContentType\Form\FormBuilder;
use Symfony\Cmf\Component\ContentType\Metadata\ClassMetadata;
use Symfony\Cmf\Component\ContentType\Metadata\PropertyMetadata;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;

class FormBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $builder;

    public function setUp()
    {
        $this->metadataFactory = $this->prophesize(MetadataFactory::class);
        $this->formFactory = $this->prophesize(FormFactoryInterface::class);
        $this->fieldRegistry = $this->prophesize(FieldRegistry::class);

        $this->builder = new FormBuilder(
            $this->metadataFactory->reveal(),
            $this->formFactory->reveal(),
            $this->fieldRegistry->reveal()
        );

        $this->classMetadata = $this->prophesize(ClassMetadata::class);
        $this->propertyMetadata1 = $this->prophesize(PropertyMetadata::class);
        $this->formBuilder = $this->prophesize(FormBuilderInterface::class);
        $this->childFormBuilder = $this->prophesize(FormBuilderInterface::class);
        $this->field1 = $this->prophesize(FieldInterface::class);
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

        $this->builder->buildFormForContent(new \stdClass());
    }

    /**
     * It should build a form for the given content.
     */
    public function testBuildForm()
    {
        $content = new \stdClass();
        $this->formFactory->createBuilder(FormType::class, $content)->willReturn(
            $this->formBuilder->reveal()
        );

        $this->metadataFactory->getMetadataForClass('stdClass')->willReturn(
            $this->classMetadata->reveal()
        );
        $this->classMetadata->getPropertyMetadata()->willReturn([
            $this->propertyMetadata1->reveal(),
        ]);
        $this->propertyMetadata1->getType()->willReturn('foobar');
        $this->fieldRegistry->get('foobar')->willReturn($this->field1->reveal());
        $this->propertyMetadata1->getName()->willReturn('prop1');
        $this->field1->getFormType()->willReturn('Type\Fqn\Text');
        $this->propertyMetadata1->getFormOptions()->willReturn([
            'one' => 'two',
        ]);

        $this->formBuilder->add(
            'prop1',
            'Type\Fqn\Text',
            [
                'one' => 'two',
            ]
        )->willReturn($this->childFormBuilder->reveal())->shouldBeCalled();

        $builder = $this->builder->buildFormForContent($content);

        $this->assertSame($this->formBuilder->reveal(), $builder);
    }
}
