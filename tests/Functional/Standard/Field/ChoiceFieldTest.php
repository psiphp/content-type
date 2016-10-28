<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\Standard\Field\ChoiceField;

class ChoiceFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'choice';
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
