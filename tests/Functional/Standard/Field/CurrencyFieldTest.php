<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class CurrencyFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'currency';
    }

    protected function getDefaultData()
    {
        return 'eur';
    }
}
