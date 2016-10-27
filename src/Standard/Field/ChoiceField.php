<?php

namespace Psi\Component\ContentType\Standard\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;
use Psi\Component\ContentType\Standard\Storage\StringType;
use Psi\Component\ContentType\Standard\View\ScalarType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChoiceField implements FieldInterface
{
    public function getViewType(): string
    {
        return ScalarType::class;
    }

    public function getFormType(): string
    {
        return ChoiceType::class;
    }

    public function getStorageType(): string
    {
        return StringType::class;
    }

    public function configureOptions(FieldOptionsResolver $options)
    {
        $options->setDefaults([
            'choices' => [],
            'expanded' => false,
            'group_by' => null,
            'multiple' => false,
            'placeholder' => null,
            'preferred_choices' => [],
        ]);

        $options->setFormMapper(function ($options) {
            return $options;
        });
    }
}
