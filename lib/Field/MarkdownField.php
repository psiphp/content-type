<?php

namespace Symfony\Cmf\Component\ContentType\Field;

class MarkdownField implements FieldInterface
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
