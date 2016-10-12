<?php

namespace Psi\Component\ContentType\Metadata\Driver;

use Metadata\Driver\AdvancedDriverInterface;
use Psi\Component\ContentType\Metadata\ClassMetadata;
use Psi\Component\ContentType\Metadata\PropertyMetadata;

class ArrayDriver implements AdvancedDriverInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        if (!isset($this->config[$class->getName()])) {
            return;
        }

        $config = array_merge([
            'fields' => [],
        ], $this->config[$class->getName()]);

        $classMetadata = new ClassMetadata($class->getName());
        $defaults = [
            'type' => null,
            'role' => null,
            'group' => null,
            'options' => [],
        ];

        foreach ($config['fields'] as $fieldName => $fieldConfig) {
            if ($diff = array_diff(array_keys($fieldConfig), array_keys($defaults))) {
                throw new \InvalidArgumentException(sprintf(
                    'Invalid configuration key(s) "%s" for field "%s" on class "%s", valid keys: "%s"',
                    implode('", "', $diff), $fieldName, $class->getName(), implode('", "', array_keys($defaults))
                ));
            }

            $fieldConfig = array_merge($defaults, $fieldConfig);
            $propertyMetadata = new PropertyMetadata(
                $class->getName(),
                $fieldName,
                $fieldConfig['type'],
                $fieldConfig['role'],
                $fieldConfig['group'],
                $fieldConfig['options']
            );

            $classMetadata->addPropertyMetadata($propertyMetadata);
        }

        return $classMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllClassNames()
    {
        return array_keys($this->config);
    }
}
