<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class CountryFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'country';
    }

    protected function getDefaultData()
    {
        return 'fr';
    }
}
