<?php

namespace Symfony\Cmf\Component\ContentType\Tests\Unit\Field;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Cmf\Component\ContentType\View\ScalarView;
use Symfony\Cmf\Component\ContentType\Field\TextField;

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
