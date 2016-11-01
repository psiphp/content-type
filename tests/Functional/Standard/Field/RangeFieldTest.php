<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class RangeFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'range';
    }

    protected function getDefaultData()
    {
        return 12.5;
    }
}
