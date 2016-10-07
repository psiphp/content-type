<?php

namespace Psi\Component\ContentType\Form\Extension;

use Metadata\MetadataFactoryInterface;
use Psi\Component\ContentType\FieldRegistry;
use Psi\Component\ContentType\Form\Extension\Type\SurrogateType;
use Symfony\Component\Form\AbstractExtension;

/**
 * Form type extension to provide form types for user content-type-managed
 * classes in addition to content-type component specific types.
 */
class FieldExtension extends AbstractExtension
{
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var FieldRegistry
     */
    private $fieldRegistry;

    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        FieldRegistry $fieldRegistry
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->fieldRegistry = $fieldRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function hasType($type)
    {
        if ($this->metadataFactory->getMetadataForClass($type)) {
            return true;
        }

        return parent::hasType($type);
    }

    /**
     * {@inheritdoc}
     */
    public function loadTypes()
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getType($type)
    {
        if (parent::hasType($type)) {
            return parent::getType($type);
        }

        $metadata = $this->metadataFactory->getMetadataForClass($type);

        if (!$metadata) {
            throw new \InvalidArgumentException(sprintf(
                'The type "%s" cannot be loaded by this extension',
                $type
            ));
        }

        $surrogateType = new SurrogateType(
            $type,
            $this->fieldRegistry,
            $metadata
        );

        return $surrogateType;
    }
}
