<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class LocaleFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'locale';
    }

    protected function getDefaultData()
    {
        return 'en';
    }
}
