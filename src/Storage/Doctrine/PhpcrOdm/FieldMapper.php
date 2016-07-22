<?php

namespace Symfony\Cmf\Component\ContentType\Storage\Doctrine\PhpcrOdm;

use Symfony\Cmf\Component\ContentType\Mapping\StringMapping;
use Symfony\Cmf\Component\ContentType\Mapping\IntegerMapping;
use Symfony\Cmf\Component\ContentType\Mapping\CompoundMapping;

class FieldMapper
{
    const CONTENTTYPE_PREFIX = 'cmfct';

    public function __invoke($fieldName, $fieldMapping, $metadata)
    {
        if ($fieldMapping instanceof CompoundMapping) {
            $metadata->mapChild([
                'fieldName' => $fieldName,
                'nodeName' => sprintf(
                    '%s:%s',
                    self::CONTENTTYPE_PREFIX,
                    $fieldName
                )
            ]);
            return;
        }

        if ($fieldMapping instanceof StringMapping) {
            $metadata->mapField([
                'fieldName' => $fieldName,
                'type' => 'string',
            ]);

            return;
        }

        if ($fieldMapping instanceof IntegerMapping) {
            $metadata->mapField([
                'fieldName' => $fieldName,
                'type' => 'long',
            ]);

            return;
        }

        throw new \RuntimeException(sprintf(
            'Do not know how to map field of type "%s"',
            get_class($fieldMapping)
        ));
    }
}
