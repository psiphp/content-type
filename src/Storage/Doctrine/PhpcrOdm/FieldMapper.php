<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Component\ContentType\Storage\Doctrine\PhpcrOdm;

use Symfony\Cmf\Component\ContentType\Mapping\CompoundMapping;
use Symfony\Cmf\Component\ContentType\Mapping\IntegerMapping;
use Symfony\Cmf\Component\ContentType\Mapping\StringMapping;

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
                ),
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
