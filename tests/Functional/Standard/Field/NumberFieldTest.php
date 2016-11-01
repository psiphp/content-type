<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class NumberFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'number';
    }

    protected function getDefaultData()
    {
        return 666.11;
    }
}
