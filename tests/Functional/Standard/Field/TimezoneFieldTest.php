<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class TimezoneFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'timezone';
    }

    protected function getDefaultData()
    {
        return 'Europe/Istanbul';
    }
}
