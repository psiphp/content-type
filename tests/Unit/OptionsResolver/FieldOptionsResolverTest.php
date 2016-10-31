<?php

namespace Psi\Component\ContentType\Tests\Unit\OptionsResolver;

use Psi\Component\ContentType\FieldOptions;
use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;

class FieldOptionsResolverTest extends \PHPUnit_Framework_TestCase
{
    private $resolver;

    public function setUp()
    {
        $this->resolver = new FieldOptionsResolver();
    }

    /**
     * It should resolve form options.
     */
    public function testMapFormOptions()
    {
        $this->doTestMapOptions('Form');
    }

    /**
     * It should return an empty array if no form option mapper has been set.
     */
    public function testNoFormMapper()
    {
        $this->resolver->setDefault('foo', 'bar');
        $this->resolver->setDefault('bar', 'foo');

        $this->assertEquals([], $this->resolver->resolveFormOptions(FieldOptions::create([])));
    }

    /**
     * It should pass type options directly if no mapper is provided.
     */
    public function testNoFormMapperPassDirect()
    {
        $this->resolver->setDefault('foo', 'bar');
        $this->resolver->setDefault('bar', 'foo');

        $this->assertEquals(['foo' => 'bar'], $this->resolver->resolveFormOptions(FieldOptions::create([
            'form' => [
                'foo' => 'bar',
            ],
        ])));
    }

    /**
     * It should resolve view options.
     */
    public function testMapViewOptions()
    {
        $this->doTestMapOptions('View');
    }

    /**
     * It should return an empty array if no view option mapper has been set.
     */
    public function testNoViewMapper()
    {
        $this->resolver->setDefault('foo', 'bar');
        $this->resolver->setDefault('bar', 'foo');

        $this->assertEquals([], $this->resolver->resolveViewOptions(FieldOptions::create([])));
    }

    private function doTestMapOptions($type)
    {
        $setMethod = 'set' . $type . 'Mapper';
        $resolveMethod = 'resolve' . $type . 'Options';

        $this->resolver->setDefault('foo', 'bar');
        $this->resolver->setDefault('bar', 'foo');
        $this->resolver->$setMethod(function (array $options, array $shared) {
            return [
                'baz' => $shared['bar'],
                'ban' => 'bon',
            ];
        });

        $options = $this->resolver->resolve([]);
        $this->assertEquals([
            'foo' => 'bar',
            'bar' => 'foo',
        ], $options);

        $this->assertEquals([
            'baz' => 'foo',
            'ban' => 'bon',
        ], $this->resolver->$resolveMethod(FieldOptions::create([])));
    }
}
