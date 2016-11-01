<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class DateFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'date';
    }

    protected function getDefaultData()
    {
        return new \DateTime('2016-01-01 00:00:00Z');
    }
}
