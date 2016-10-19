<?php

declare(strict_types=1);

namespace Psi\Component\ContentType;

class FieldLoader
{
    private $fieldRegistry;
    private $fields = [];

    public function __construct(FieldRegistry $fieldRegistry)
    {
        $this->fieldRegistry = $fieldRegistry;
    }

    public function load(string $type, array $options = []): Field
    {
        $hash = md5(serialize($options)) . $type;

        if (isset($this->fields[$hash])) {
            return $this->fields[$hash];
        }

        $field = $this->fieldRegistry->get($type);
        $field = new Field($field, $options);

        $this->fields[$hash] = $field;

        return $field;
    }
}
