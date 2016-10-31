<?php

namespace Psi\Component\ContentType\Tests\Unit;

use Prophecy\Argument;
use Psi\Component\ContentType\Field;
use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\FieldOptions;
use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;

class FieldTest extends \PHPUnit_Framework_TestCase
{
    private $innerField;

    public function setUp()
    {
        $this->innerField = $this->prophesize(FieldInterface::class);
    }

    /**
     * It should resolve <---> options.
     * It should return the <---> type.
     *
     * @dataProvider provideOptionsResolution
     */
    public function testOptionResolution(string $type)
    {
        $optionsMethod = sprintf('get%sOptions', $type);
        $mapperMethod = sprintf('set%sMapper', $type);

        $field = $this->createField([
            'shared' => [
                'foo' => 'bar',
                'bar' => 'foo',
            ],
        ]);

        $this->innerField->configureOptions(Argument::type(FieldOptionsResolver::class))->will(function ($args) use ($mapperMethod) {
            $args[0]->setDefaults([
                'foo' => '0',
                'bar' => '2',
            ]);
            $args[0]->$mapperMethod(function ($options, $shared) {
                return [
                    'foo' => $shared['foo'],
                    'car' => 'zar',
                ];
            });
        })->shouldBeCalledTimes(1);

        // do twice to confirm caching
        $field->$optionsMethod();
        $resolved = $field->$optionsMethod();

        $this->assertEquals([
            'foo' => 'bar',
            'car' => 'zar',
        ], $resolved);
    }

    /**
     * It should function correctly with no mappers.
     *
     * @dataProvider provideOptionsResolution
     */
    public function testNoMapping($type)
    {
        $optionsMethod = sprintf('get%sOptions', $type);
        $field = $this->createField([
            'shared' => [
                'foo' => 'bar',
                'bar' => 'foo',
            ],
        ]);

        $this->innerField->configureOptions(Argument::type(FieldOptionsResolver::class))->shouldBeCalled();

        // do twice to confirm caching
        $field->$optionsMethod();
        $resolved = $field->$optionsMethod();

        $this->assertEquals([], $resolved);
    }

    public function provideOptionsResolution()
    {
        return [
            [
                'View',
            ],
            [
                'Form',
            ],
            [
                'Storage',
            ],
        ];
    }

    /**
     * It should return the appropriate types.
     */
    public function testTypes()
    {
        $this->innerField->getFormType()->willReturn('FormType');
        $this->innerField->getViewType()->willReturn('ViewType');
        $this->innerField->getStorageType()->willReturn('StorageType');

        $field = $this->createField([]);
        $this->assertEquals('FormType', $field->getFormType());
        $this->assertEquals('StorageType', $field->getStorageType());
        $this->assertEquals('ViewType', $field->getViewType());
    }

    private function createField(array $options)
    {
        return new Field($this->innerField->reveal(), FieldOptions::create($options));
    }
}
