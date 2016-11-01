<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class CheckboxFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'checkbox';
    }

    protected function getDefaultData()
    {
        return true;
    }
}
