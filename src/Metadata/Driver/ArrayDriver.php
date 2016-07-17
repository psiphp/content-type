<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Component\ContentType\Metadata\Driver;

use Metadata\Driver\DriverInterface;
use Symfony\Cmf\Component\ContentType\Metadata\ClassMetadata;
use Symfony\Cmf\Component\ContentType\Metadata\PropertyMetadata;

class ArrayDriver implements DriverInterface
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
     * @param \ReflectionClass $class
     *
     * @return \Metadata\ClassMetadata
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        if (!isset($this->config[$class->getName()])) {
            return;
        }

        $config = array_merge([
            'driver' => null,
            'fields' => [],
        ], $this->config[$class->getName()]);

        $classMetadata = new ClassMetadata($class->getName(), $config['driver']);

        foreach ($config['properties'] as $fieldName => $fieldConfig) {
            $fieldConfig = array_merge([
                'type' => null,
                'options' => [],
                'form_options' => [],
            ], $fieldConfig);
            $propertyMetadata = new PropertyMetadata(
                $class->getName(),
                $fieldName,
                $fieldConfig['type'],
                $fieldConfig['options'],
                $fieldConfig['form_options']
            );

            $classMetadata->addPropertyMetadata($propertyMetadata);
        }

        return $classMetadata;
    }
}