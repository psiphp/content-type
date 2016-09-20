<?php

namespace Psi\Component\ContentType\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\Form\Extension\Type\FieldCollectionType;
use Psi\Component\ContentType\MappingBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Psi\Component\ContentType\ViewInterface;
use Symfony\Component\Form\FormTypeInterface;
use Psi\Component\ContentType\MappingInterface;

class CollectionField implements FieldInterface
{
    public function getViewType(): ViewInterface
    {
        return CollectionView::class;
    }

    public function getFormType(): FormTypeInterface
    {
        return FieldCollectionType::class;
    }

    public function getMapping(MappingBuilder $builder): MappingInterface
    {
        return $builder->collection();
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setRequired([
            'entry_type',
            'allow_add',
        ]);
    }
}
