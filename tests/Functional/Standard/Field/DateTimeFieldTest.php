<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\Standard\Field\ChoiceField;

class DateTimeFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'datetime';
    }

    protected function getDefaultData()
    {
        return new \DateTime();
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
