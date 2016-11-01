<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class IntegerFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'integer';
    }

    protected function getDefaultData()
    {
        return 42;
    }
}
