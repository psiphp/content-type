<?php

namespace Symfony\Cmf\Component\ContentType\Form;

use Metadata\MetadataFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Metadata\NullMetadata;
use Symfony\Cmf\Component\ContentType\FieldRegistry;

class FormBuilder
{
    private $metadataFactory;
    private $formFactory;
    private $fieldRegistry;

    public function __construct(
        MetadataFactory $metadataFactory,
        FormFactoryInterface $formFactory,
        FieldRegistry $fieldRegistry
    )
    {
        $this->metadataFactory = $metadataFactory;
        $this->formFactory = $formFactory;
        $this->fieldRegistry = $fieldRegistry;
    }

    public function buildFormForContent($content)
    {
        $metadata = $this->metadataFactory->getMetadataForClass(get_class($content));

        if ($metadata instanceof NullMetadata) {
            throw new \RuntimeException(sprintf(
                'Class "%s" is not mapped',
                $metadata->name
            ));
        }

        $builder = $this->formFactory->create('content', $content);

        foreach ($metadata->getPropertyMetadata() as $propertyMetadata) {
            $field = $this->fieldRegistry->get($propertyMetadata->getType());
            $builder->add(
                $propertyMetadata->getName(),
                $field->getFormType(),
                $propertyMetadata->getFormOptions()
            );
        }
    }
}
