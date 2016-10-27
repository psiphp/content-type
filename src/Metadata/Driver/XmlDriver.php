<?php

namespace Psi\Component\ContentType\Metadata\Driver;

use Metadata\Driver\AbstractFileDriver;
use Psi\Component\ContentType\Metadata\ClassMetadata;
use Psi\Component\ContentType\Metadata\PropertyMetadata;

class XmlDriver extends AbstractFileDriver
{
    const XML_NAMESPACE = 'http://github.com/psiphp/content-type/mapping';

    /**
     * {@inheritdoc}
     */
    public function loadMetadataFromFile(\ReflectionClass $class, $path)
    {
        $classMetadata = new ClassMetadata($class->getName());

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0');
        $dom->load($path);

        if (!$dom->schemaValidate(__DIR__ . '/../../../schema/mapping.xsd')) {
            $message = array_reduce(libxml_get_errors(), function ($foo, $error) {
                return $error->message;
            });
            throw new \InvalidArgumentException(sprintf(
                'Could not validate XML mapping at "%s": %s',
                $path, $message
            ));
        }

        $xpath = new \DOMXpath($dom);
        $xpath->registerNamespace('psict', self::XML_NAMESPACE);


        foreach ($xpath->query('//psict:class') as $classEl) {
            $classAttr = $classEl->getAttribute('name');

            if ($classAttr !== $class->getName()) {
                throw new \InvalidArgumentException(sprintf(
                    'Expected class name to be "%s" but it is mapped as "%s"',
                    $class->getName(), $classAttr
                ));
            }

            foreach ($xpath->query('./psict:field', $classEl) as $fieldEl) {
                $options = $this->extractOptions($xpath, $fieldEl);
                $propertyMetadata = new PropertyMetadata(
                    $class->getName(),
                    $fieldEl->getAttribute('name'),
                    $fieldEl->getAttribute('type'),
                    $fieldEl->getAttribute('role'),
                    $fieldEl->getAttribute('group'),
                    $options
                );

                $classMetadata->addPropertyMetadata($propertyMetadata);
            }
        }

        return $classMetadata;
    }

    private function extractOptions(\DOMXPath $xpath, \DOMElement $fieldEl)
    {
        $options = [];

        foreach ($xpath->query('./psict:option', $fieldEl) as $optionEl) {
            if ($optionEl->getAttribute('type') === 'collection') {
                $options[$optionEl->getAttribute('name')] = $this->extractOptions($xpath, $optionEl);
                continue;
            }

            $options[$optionEl->getAttribute('name')] = $optionEl->nodeValue;
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return 'xml';
    }
}
