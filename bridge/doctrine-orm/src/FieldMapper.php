<?php

declare(strict_types=1);

namespace Psi\Bridge\ContentType\Doctrine\Orm;

use Doctrine\ORM\Mapping\ClassMetadata;
use Psi\Component\ContentType\Field;
use Psi\Component\ContentType\Standard\Storage\CollectionType;
use Psi\Component\ContentType\Standard\Storage\DateTimeType;
use Psi\Component\ContentType\Standard\Storage\IntegerType;
use Psi\Component\ContentType\Standard\Storage\ObjectType;
use Psi\Component\ContentType\Standard\Storage\ReferenceType;
use Psi\Component\ContentType\Standard\Storage\StringType;

class FieldMapper
{
    public function __invoke($fieldName, Field $field, ClassMetadata $metadata, array $extraOptions = [])
    {
        $type = $field->getStorageType();
        $options = $field->getStorageOptions();

        if ($type === ObjectType::class) {
            $metadata->mapField([
                'fieldName' => $fieldName,
                'type' => 'object',
            ]);

            return;
        }

        if ($type === CollectionType::class) {
            return;
        }

        if ($type === StringType::class) {
            $metadata->mapField([
                'fieldName' => $fieldName,
                'type' => 'string',
                'nullable' => true,
            ]);

            return;
        }

        if ($type === IntegerType::class) {
            $metadata->mapField([
                'fieldName' => $fieldName,
                'type' => 'integer',
                'nullable' => true,
            ]);

            return;
        }

        if ($type === DateTimeType::class) {
            $metadata->mapField([
                'fieldName' => $fieldName,
                'type' => 'date',
                'nullable' => true,
            ]);

            return;
        }

        if ($type === ReferenceType::class) {
            if (false === isset($options['class'])) {
                throw new \InvalidArgumentException(sprintf(
                    'Doctrine ORM storage requires that you provide the "class" option for reference mapping for "%s::$%s"',
                    $metadata->getName(), $fieldName
                ));
            }

            $metadata->mapManyToOne([
                'fieldName' => $fieldName,
                'targetEntity' => $options['class'],
                'cascade' => ['all'],
            ]);

            return;
        }

        throw new \RuntimeException(sprintf(
            'Do not know how to map field of type "%s"',
            get_class($type)
        ));
    }
}
