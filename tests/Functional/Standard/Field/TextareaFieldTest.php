<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class TextareaFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'textarea';
    }

    protected function getDefaultData()
    {
        return <<<'EOT'
Hello
EOT;
    }
}
