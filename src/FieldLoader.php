<?php

declare(strict_types=1);

namespace Psi\Component\ContentType;

use Psi\Component\ContentType\Storage\TypeFactory;

class FieldLoader
{
    private $fieldRegistry;
    private $typeFactory;
    private $fields = [];

    public function __construct(TypeFactory $typeFactory, FieldRegistry $fieldRegistry)
    {
        $this->fieldRegistry = $fieldRegistry;
        $this->typeFactory = $typeFactory;
    }

    public function load(string $type, array $options = []): LoadedField
    {
        $hash = md5(serialize($options)) . $type;

        if (isset($this->fields[$hash])) {
            return $this->fields[$hash];
        }

        $field = $this->fieldRegistry->get($type);
        $field = new LoadedField($this->typeFactory, $field, $options);

        $this->fields[$hash] = $field;

        return $field;
    }
}
