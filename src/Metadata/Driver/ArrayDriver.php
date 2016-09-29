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

        foreach ($config['properties'] as $fieldName => $fieldConfig) {
            $fieldConfig = array_merge([
                'type' => null,
                'role' => null,
                'options' => [],
            ], $fieldConfig);
            $propertyMetadata = new PropertyMetadata(
                $class->getName(),
                $fieldName,
                $fieldConfig['type'],
                $fieldConfig['role'],
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
