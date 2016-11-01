<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Standard\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;
use Psi\Component\ContentType\Standard\Storage as Storage;
use Psi\Component\ContentType\Standard\View as View;
use Symfony\Component\Form\Extension\Core\Type as Form;

class UrlField implements FieldInterface
{
    public function getViewType(): string
    {
        return View\UrlType::class;
    }

    public function getFormType(): string
    {
        return Form\UrlType::class;
    }

    public function getStorageType(): string
    {
        return Storage\StringType::class;
    }

    public function configureOptions(FieldOptionsResolver $options)
    {
    }
}
