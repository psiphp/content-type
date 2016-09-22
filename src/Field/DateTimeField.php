<?php

namespace Psi\Component\ContentType\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\MappingBuilder;
use Psi\Component\ContentType\View\ScalarView;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeField implements FieldInterface
{
    public function getViewType()
    {
        return ScalarView::class;
    }

    public function getFormType()
    {
        return DateTimeType::class;
    }

    public function getMapping(MappingBuilder $builder)
    {
        return $builder->single('datetime');
    }

    public function configureOptions(OptionsResolver $options)
    {
    }
}
