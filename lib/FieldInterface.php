<?php

namespace Symfony\Cmf\Component\ContentType;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface FieldInterface
{
    public function getViewType();

    public function getFormType();

    public function configureOptions(OptionsResolver $options);
}
