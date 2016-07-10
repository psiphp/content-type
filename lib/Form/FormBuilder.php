<?php

namespace Symfony\Cmf\Component\ContentType\Form;

use Metadata\MetadataFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Metadata\NullMetadata;
use Symfony\Cmf\Component\ContentType\FieldRegistry;
use Symfony\Cmf\Component\ContentType\Form\Transformer\SerializeTransformer;

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

    public function buildFormForContent($contentType, $content = null)
    {
        $metadata = $this->metadataFactory->getMetadataForClass($contentType);

        if ($metadata instanceof NullMetadata) {
            throw new \RuntimeException(sprintf(
                'Class "%s" is not mapped',
                $metadata->name
            ));
        }

        $builder = $this->formFactory->create('content', $content);

        foreach ($metadata->getPropertyMetadata() as $propertyMetadata) {
            $field = $this->fieldRegistry->get($propertyMetadata->getType());
            $formOptions = $propertyMetadata->getFormOptions();

            $formField = $builder->add(
                $propertyMetadata->getName(),
                $field->getFormType(),
                $formOptions
            );

            // configure the default options on the form, the options havn't
            // been resolved yet.
            $field->buildOptions($formField->getOptionsResolver());

            // for now always serialize "compound" types.
            if ($formField->getCompound()) {
                $formField->addModelTransformer(new SerializeTransformer());
            }
        }

        return $builder;
    }
}
