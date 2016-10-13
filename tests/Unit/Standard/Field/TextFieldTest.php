<?php

namespace Psi\Component\ContentType\Tests\Unit\Standard\Field;

use Psi\Component\ContentType\Standard\Field\TextField;
use Psi\Component\ContentType\Standard\View\ScalarView;
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
