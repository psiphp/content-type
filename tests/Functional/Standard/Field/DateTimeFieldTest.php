<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class DateTimeFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'datetime';
    }

    protected function getDefaultData()
    {
        return new \DateTime('2016-01-01 00:00:00Z');
    }
}
