<?php

namespace Symfony\Cmf\Component\ContentType\Field;

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

    public function getDefaultFormConfig()
    {
        return [];
    }

    public function getDefaultViewConfig()
    {
        return [];
    }
}
