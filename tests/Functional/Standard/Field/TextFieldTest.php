<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

use Psi\Component\ContentType\Standard\Field\TextField;
use Psi\Component\ContentType\Standard\View\ScalarType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Psi\Component\ContentType\FieldInterface;

class TextFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'text';
    }

    protected function getDefaultData()
    {
        return 'one';
    }

    public function provideValidConfigs(): array
    {
        return [ 
            [
                []
            ]
        ];
    }
}
