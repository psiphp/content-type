<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class MoneyFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'money';
    }

    protected function getDefaultData()
    {
        return 100;
    }
}
