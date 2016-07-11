<?php

namespace Symfony\Cmf\Component\ContentType\Field;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Cmf\Component\ContentType\FieldInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Cmf\Component\ContentType\View\ScalarView;

class TextField implements FieldInterface
{
    public function getViewType()
    {
        return ScalarView::class;
    }

    public function getFormType()
    {
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $options)
    {
    }
}
