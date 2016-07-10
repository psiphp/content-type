<?php

namespace Symfony\Cmf\Component\ContentType\Field;

use Symfony\Component\OptionsResolver\OptionsResolver;

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

    public function buildOptions(OptionsResolver $resolver)
    {
    }
}
