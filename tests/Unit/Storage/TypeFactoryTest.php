<?php

namespace Psi\Component\ContentType\Tests\Unit\Storage;

use Prophecy\Argument;
use Psi\Component\ContentType\Storage\ConfiguredType;
use Psi\Component\ContentType\Storage\TypeFactory;
use Psi\Component\ContentType\Storage\TypeInterface;
use Psi\Component\ContentType\Storage\TypeRegistry;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $factory;

    public function setUp()
    {
        $this->registry = $this->prophesize(TypeRegistry::class);
        $this->factory = new TypeFactory($this->registry->reveal());

        $this->type = $this->prophesize(TypeInterface::class);
        $this->type->configureOptions(Argument::type(OptionsResolver::class))->will(function ($args) {
            $args[0]->setDefault('default', 'one');
        });

        $this->registry->get('text')->willReturn($this->type->reveal());
    }

    /**
     * It should configured create configured types.
     */
    public function testCreate()
    {
        $type = $this->factory->create('text', ['default' => 'two']);
        $this->assertInstanceOf(ConfiguredType::class, $type);
        $this->assertEquals([
            'default' => 'two',
        ], $type->getOptions());
        $this->assertInstanceOf(get_class($this->type->reveal()), $type->getInnerType());
    }
}
