<?php

declare(strict_types=1);

namespace Psi\Component\ContentType;

use Psi\Component\ContentType\Metadata\PropertyMetadata;
use Psi\Component\ContentType\Storage\Mapping\TypeFactory;

class FieldLoader
{
    private $fieldRegistry;
    private $typeFactory;

    public function __construct(TypeFactory $typeFactory, FieldRegistry $fieldRegistry)
    {
        $this->fieldRegistry = $fieldRegistry;
        $this->typeFactory = $typeFactory;
    }

    public function loadForProperty(PropertyMetadata $property): LoadedField
    {
        $hash = spl_object_hash($property);
        if (isset($this->fields[$hash])) {
            return $this->fields[$hash];
        }

        $field = $this->fieldRegistry->get($property->getType());

        $this->fields[$hash] = new LoadedField($this->typeFactory, $field, $property->getOptions());

        return $this->fields[$hash];
    }

    public function loadByTypeAndOptions(string $type, array $options = []): LoadedField
    {
        $field = $this->fieldRegistry->get($type);
        $field = new LoadedField($this->typeFactory, $field, $options);

        return $field;
    }
}
