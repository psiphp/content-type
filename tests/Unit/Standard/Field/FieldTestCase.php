<?php

namespace Psi\Component\ContentType\Tests\Unit\Standard\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\View\TypeInterface;
use Psi\Component\ContentType\View\ViewInterface;
use Symfony\Component\Form\FormTypeInterface;

abstract class FieldTestCase extends \PHPUnit_Framework_TestCase
{
    abstract protected function getField(): FieldInterface;

    /**
     * It should return a valid view type.
     */
    public function testGetViewType()
    {
        $field = $this->getField();
        $viewType = $field->getViewType();

        $this->assertTrue(class_exists($viewType), 'Valid class returned');
        $reflection = new \ReflectionClass($viewType);
        $this->assertTrue($reflection->isSubclassOf(TypeInterface::class), 'View class is instance of ViewInterface');
    }

    /**
     * It should return a valid form type.
     */
    public function testGetFormType()
    {
        $field = $this->getField();
        $viewType = $field->getFormType();

        $this->assertTrue(class_exists($viewType), 'Valid class returned');
        $reflection = new \ReflectionClass($viewType);
        $this->assertTrue($reflection->isSubclassOf(FormTypeInterface::class), 'FormType is instance of FormTypeInterface');
    }
}
