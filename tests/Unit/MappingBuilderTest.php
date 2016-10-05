<?php

namespace Psi\Component\ContentType\Tests\Unit;

use Psi\Component\ContentType\MappingBuilder;
use Psi\Component\ContentType\MappingBuilderCompound;
use Psi\Component\ContentType\MappingInterface;
use Psi\Component\ContentType\MappingRegistry;

class MappingBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $registry;
    private $builder;

    public function setUp()
    {
        $this->registry = $this->prophesize(MappingRegistry::class);
        $this->builder = new MappingBuilder($this->registry->reveal());

        $this->mapping1 = $this->prophesize(MappingInterface::class);
        $this->mapping1->getDefaultOptions()->willReturn([
            'foobar' => 'booboo',
        ]);
    }

    /**
     * It should provide a single scalar mapping.
     */
    public function testSingleScalar()
    {
        $this->registry->get('string')->willReturn($this->mapping1->reveal());
        $mapping = $this->builder->single('string');

        $this->assertSame($this->mapping1->reveal(), $mapping->getMapping());
    }

    /**
     * It should throw an exception if an unknown option is passed to the scalar mapping.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unknown option(s) "bar", available options: "foobar"
     */
    public function testSingleScalarUnkownOption()
    {
        $this->registry->get('string')->willReturn($this->mapping1->reveal());
        $this->builder->single('string', [ 'bar' => 'bar' ]);
    }

    /**
     * It should allow to build a compound mapping.
     */
    public function testCompound()
    {
        $classFqn = 'My\Compound\DataTransferObject';
        $this->registry->get('string')->willReturn($this->mapping1->reveal());

        $mapping = $this->builder->compound($classFqn)
            ->map('foobar', 'string', [ 'foobar' => 'barfoo' ])
            ->map('foobar_barfoo', 'string');

        $this->assertInstanceOf(MappingBuilderCompound::class, $mapping);
        $compound = $mapping->getCompound();

        $this->assertEquals($classFqn, $compound->getClass());
        $mapping = iterator_to_array($compound);

        $this->assertCount(2, $mapping);
        $this->assertSame($this->mapping1->reveal(), $mapping['foobar']->getMapping());
        $this->assertEquals('barfoo', $mapping['foobar']->getOption('foobar'));
    }

    /**
     * It should throw an exception if the compound map is passed an unknown option.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unknown option(s) "bar", available options: "foobar"
     */
    public function testCompoundMapUnknownOption()
    {
        $classFqn = 'My\Compound\DataTransferObject';
        $this->registry->get('string')->willReturn($this->mapping1->reveal());

        $this->builder->compound($classFqn)
            ->map('foobar', 'string', [ 'bar' => 'barfoo' ]);
    }
}
