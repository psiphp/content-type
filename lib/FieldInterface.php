<?php

namespace Symfony\Cmf\Component\ContentType;

interface FieldInterface
{
    public function getViewType();

    public function getFormType();

    public function getDefaultFormOptions();

    public function getDefaultViewOptions();
}
