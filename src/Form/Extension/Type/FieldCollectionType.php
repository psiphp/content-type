<?php

namespace Psi\Component\ContentType\Form\Extension\Type;

use Psi\Component\ContentType\FieldRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldCollectionType extends AbstractType
{
    private $registry;

    public function __construct(FieldRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setNormalizer('entry_type', function (Options $options, $value) {
            // get the field type
            $field = $this->registry->get($value);

            return $field->getFormType();
        });
    }

    public function getParent()
    {
        return CollectionType::class;
    }
}
