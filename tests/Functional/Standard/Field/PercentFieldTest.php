<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class PercentFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'percent';
    }

    protected function getDefaultData()
    {
        return 12.13;
    }
}
