<?php

namespace Psi\Component\ContentType;

final class FieldOptions
{
    private $sharedOptions;
    private $formOptions;
    private $viewOptions;
    private $storageOptions;

    public static function create(array $options)
    {
        $defaults = [
            'shared' => [],
            'form' => [],
            'view' => [],
            'storage' => [],
        ];

        if ($diff = array_diff(array_keys($options), array_keys($defaults))) {
            throw new \InvalidArgumentException(sprintf(
                'Unexpected field option keys: "%s". Allowed keys: "%s"',
                implode('", "', $diff), implode('", "', array_keys($defaults))
            ));
        }

        $options = array_merge($defaults, $options);

        $instance = new self();
        $instance->sharedOptions = $options['shared'];
        $instance->formOptions = $options['form'];
        $instance->viewOptions = $options['view'];
        $instance->storageOptions = $options['storage'];

        return $instance;
    }

    public function getSharedOptions()
    {
        return $this->sharedOptions;
    }

    public function getFormOptions()
    {
        return $this->formOptions;
    }

    public function getViewOptions()
    {
        return $this->viewOptions;
    }

    public function getStorageOptions()
    {
        return $this->storageOptions;
    }
}
