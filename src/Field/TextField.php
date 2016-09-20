<?php

namespace Psi\Component\ContentType\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\MappingBuilder;
use Psi\Component\ContentType\View\ScalarView;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormTypeInterface;
use Psi\Component\ContentType\ViewInterface;
use Psi\Component\ContentType\MappingInterface;

class TextField implements FieldInterface
{
    public function getViewType(): ViewInterface
    {
        return ScalarView::class;
    }

    public function getFormType(): FormTypeInterface
    {
        return TextType::class;
    }

    public function getMapping(MappingBuilder $builder): MappingInterface
    {
        return $builder->single('string');
    }

    public function configureOptions(OptionsResolver $options)
    {
    }
}
