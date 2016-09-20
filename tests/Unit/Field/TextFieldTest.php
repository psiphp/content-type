<?php

namespace Psi\Component\ContentType\Tests\Unit\Field;

use Psi\Component\ContentType\Field\TextField;
use Psi\Component\ContentType\View\ScalarView;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TextFieldTest extends \PHPUnit_Framework_TestCase
{
    private $field;

    public function setUp()
    {
        $this->field = new TextField();
    }

    public function testGetFormType()
    {
        $formType = $this->field->getFormType();
        $this->assertEquals(TextType::class, $formType);
    }

    public function testGetViewType()
    {
        $viewType = $this->field->getViewType();
        $this->assertEquals(ScalarView::class, $viewType);
    }
}
