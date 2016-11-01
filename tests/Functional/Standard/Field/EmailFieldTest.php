<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class EmailFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'email';
    }

    protected function getDefaultData()
    {
        return 'dan@example.com';
    }
}
