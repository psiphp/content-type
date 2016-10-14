<?php

namespace Psi\Component\ContentType\Tests\Unit;

use Prophecy\Argument;
use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\LoadedField;
use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;
use Psi\Component\ContentType\Storage\ConfiguredType;
use Psi\Component\ContentType\Storage\TypeFactory;

class LoadedFieldTest extends \PHPUnit_Framework_TestCase
{
    private $loadedField;
    private $field;
    private $configuredType;
    private $typeFactory;

    public function setUp()
    {
        $this->typeFactory = $this->prophesize(TypeFactory::class);
        $this->field = $this->prophesize(FieldInterface::class);

        $this->loadedField = new LoadedField(
            $this->typeFactory->reveal(),
            $this->field->reveal(),
            [
                'option' => 'one',
            ]
        );

        $this->configuredType = $this->prophesize(ConfiguredType::class);

        $this->field->configureOptions(Argument::type(FieldOptionsResolver::class))->will(function ($args) {
            $args[0]->setDefaults([
                'option' => 'three',
            ]);
            $args[0]->setFormMapper(function ($options) {
                return [
                    'hello' => 'goodbye',
                    'goodbye' => $options['option'],
                ];
            });
            $args[0]->setViewMapper(function ($options) {
                return [
                    'view' => 'goodbye',
                    'hello' => $options['option'],
                ];
            });
        });
    }

    /**
     * It should return the form options.
     */
    public function testFormOptions()
    {
        $options = $this->loadedField->getFormOptions();
        $this->assertEquals([
            'hello' => 'goodbye',
            'goodbye' => 'one',
        ], $options);
    }

    /**
     * It should return the view options.
     */
    public function testViewOptions()
    {
        $options = $this->loadedField->getViewOptions();
        $this->assertEquals([
            'view' => 'goodbye',
            'hello' => 'one',
        ], $options);
    }

    /**
     * It should return the storage type.
     */
    public function testStorageType()
    {
        $this->field->getStorageType(Argument::type(TypeFactory::class))->willReturn(
            $this->configuredType->reveal()
        );
        $type = $this->loadedField->getStorageType();
        $this->assertSame($this->configuredType->reveal(), $type);
    }

    /**
     * It should return the inner field.
     */
    public function testGetInnerField()
    {
        $field = $this->loadedField->getInnerField();

        $this->assertSame($this->field->reveal(), $field);
    }
}
