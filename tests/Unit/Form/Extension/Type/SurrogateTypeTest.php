<?php

namespace Psi\Component\ContentType\Tests\Unit\Form\Extension\Type;

use Prophecy\Argument;
use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\FieldRegistry;
use Psi\Component\ContentType\Form\Extension\Type\SurrogateType;
use Psi\Component\ContentType\Metadata\ClassMetadata;
use Psi\Component\ContentType\Metadata\PropertyMetadata;
use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\TypeTestCase;

class SurrogateTypeTest extends TypeTestCase
{
    private $classMetadata;
    private $fieldRegistry;
    private $type;
    private $field;

    public function setUp()
    {
        parent::setUp();
        $this->classMetadata = $this->prophesize(ClassMetadata::class);
        $this->fieldRegistry = $this->prophesize(FieldRegistry::class);

        $this->type = new SurrogateType(
            'ContentFqn',
            $this->fieldRegistry->reveal(),
            $this->classMetadata->reveal()
        );

        $this->property1 = $this->prophesize(PropertyMetadata::class);
        $this->field = $this->prophesize(FieldInterface::class);
        $this->formBuilder = $this->factory->createBuilder(FormType::class);
    }

    /**
     * It should build a form.
     */
    public function testBuildForm()
    {
        $type = 'foo';
        $propertyName = 'propname';
        $formType = TextType::class;

        $property = $this->createProperty($propertyName, $type, null, ['data' => 'value']);
        $this->classMetadata->getPropertyMetadata()->willReturn([
            $property,
        ]);
        $this->field->getFormType()->willReturn($formType);
        $this->fieldRegistry->get($type)->willReturn($this->field);
        $this->field->configureOptions(Argument::type(FieldOptionsResolver::class))->will(function ($args) {
            $args[0]->setDefault('data', 'bar');
            $args[0]->setFormMapper(function ($options) {
                return $options;
            });
        });

        $this->type->buildForm($this->formBuilder, []);
        $this->assertTrue($this->formBuilder->has($propertyName));
        $this->assertInstanceOf($formType, $this->formBuilder->get($propertyName)->getType()->getInnerType());
    }

    /**
     * It should build a form with "groups".
     */
    public function testBuildFormGroupsnull()
    {
        $type = 'hello';
        $formType = TextType::class;

        $property1 = $this->createProperty('ungrouped_prop', $type, null, ['data' => 'value']);
        $property2 = $this->createProperty('grouped_1', $type, 'hello', ['data' => 'value']);
        $property3 = $this->createProperty('grouped_2', $type, 'hello', ['data' => 'value']);
        $this->classMetadata->getPropertyMetadata()->willReturn([
            $property1,
            $property2,
            $property3,
        ]);

        $this->field->getFormType()->willReturn($formType);
        $this->fieldRegistry->get($type)->willReturn($this->field);
        $this->field->configureOptions(Argument::type(FieldOptionsResolver::class))->will(function ($args) {
            $args[0]->setDefault('data', 'bar');
            $args[0]->setFormMapper(function ($options) {
                return $options;
            });
        });

        $this->type->buildForm($this->formBuilder, []);

        $this->assertTrue($this->formBuilder->has('ungrouped_prop'));
        $this->assertInstanceOf(
            TextType::class,
            $this->formBuilder->get('ungrouped_prop')->getType()->getInnerType()
        );
        $this->assertTrue($this->formBuilder->has('hello'));
        $this->assertTrue($this->formBuilder->get('hello')->has('grouped_1'));
        $this->assertInstanceOf(
            TextType::class,
            $this->formBuilder->get('hello')->get('grouped_1')->getType()->getInnerType()
        );
        $this->assertTrue($this->formBuilder->get('hello')->has('grouped_2'));
    }

    private function createProperty(string $propertyName, string $type, string $group = null, array $options)
    {
        $property = $this->prophesize(PropertyMetadata::class);
        $property->getType()->willReturn($type);
        $property->getGroup()->willReturn($group);
        $property->getOptions()->willReturn($options);
        $property->getName()->willReturn($propertyName);

        return $property;
    }
}
