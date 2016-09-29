<?php

namespace Psi\Component\ContentType\Tests\Unit\Metadata;

use Psi\Component\ContentType\Metadata\ClassMetadata;
use Psi\Component\ContentType\Metadata\PropertyMetadata;

class ClassMetadataTest extends \PHPUnit_Framework_TestCase
{
    private $metadata;

    public function setUp()
    {
        $this->metadata = new ClassMetadata(TestClass::class);
    }

    /**
     * It should return a property by role.
     */
    public function testReturnPropertyByRole()
    {
        $this->assertFalse($this->metadata->hasPropertyByRole('title'));

        $property = new PropertyMetadata(
            TestClass::class,
            'test',
            'foo_type',
            'title',
            []
        );
        $this->metadata->addPropertyMetadata($property);
        $this->assertTrue($this->metadata->hasPropertyByRole('title'));
        $this->assertSame($property, $this->metadata->getPropertyByRole('title'));
    }

    /**
     * It should throw an exception if getting a property by non-existing role.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No property exists with role "title"
     */
    public function testGetPropertyRoleNonExisting()
    {
        $this->metadata->getPropertyByRole('title');
    }

    /**
     * It should throw an exception when adding a property with a role that has already been set.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Role "title" has already been assigned to property "test" (on property "tset")
     */
    public function testAddPropertyAlreadyExistingRole()
    {
        $property = new PropertyMetadata(
            TestClass::class,
            'test',
            'foo_type',
            'title',
            []
        );
        $this->metadata->addPropertyMetadata($property);
        $property = new PropertyMetadata(
            TestClass::class,
            'tset',
            'foo_type',
            'title',
            []
        );
        $this->metadata->addPropertyMetadata($property);
    }
}

class TestClass
{
    private $test;
    private $tset;
}
