<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class TimeFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'time';
    }

    protected function getDefaultData()
    {
        return new \DateTime('12:30:00');
    }
}
