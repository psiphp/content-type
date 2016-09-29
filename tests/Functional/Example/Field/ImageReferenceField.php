<?php

namespace Psi\Component\ContentType\Tests\Functional\Example\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\MappingBuilder;
use Psi\Component\ContentType\View\ScalarView;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageReferenceField implements FieldInterface
{
    public function getViewType()
    {
        return ScalarView::class;
    }

    public function getFormType()
    {
        return TextType::class;
    }

    public function getMapping(MappingBuilder $builder)
    {
        return $builder->single('reference');
    }

    public function configureOptions(OptionsResolver $options)
    {
    }
}
