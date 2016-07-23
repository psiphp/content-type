<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Component\ContentType\Tests\Unit;

use Symfony\Cmf\Component\ContentType\MappingBuilder;
use Symfony\Cmf\Component\ContentType\MappingBuilderCompound;
use Symfony\Cmf\Component\ContentType\MappingInterface;
use Symfony\Cmf\Component\ContentType\MappingRegistry;

class MappingBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $registry;
    private $builder;

    public function setUp()
    {
        $this->registry = $this->prophesize(MappingRegistry::class);
        $this->builder = new MappingBuilder($this->registry->reveal());

        $this->mapping1 = $this->prophesize(MappingInterface::class);
    }

    /**
     * It should provide a single scalar mapping.
     */
    public function testSingleScalar()
    {
        $this->registry->get('string')->willReturn($this->mapping1->reveal());
        $mapping = $this->builder->single('string');

        $this->assertSame($this->mapping1->reveal(), $mapping);
    }

    /**
     * It should allow to build a compound mapping.
     */
    public function testCompound()
    {
        $classFqn = 'My\Compound\DataTransferObject';
        $this->registry->get('string')->willReturn($this->mapping1->reveal());

        $mapping = $this->builder->compound($classFqn)
            ->map('foobar', 'string')
            ->map('foobar_barfoo', 'string');

        $this->assertInstanceOf(MappingBuilderCompound::class, $mapping);
        $compound = $mapping->getCompound();

        $this->assertEquals($classFqn, $compound->getClass());
        $mapping = iterator_to_array($compound);

        $this->assertCount(2, $mapping);
        $this->assertSame($this->mapping1->reveal(), $mapping['foobar']);
    }
}
