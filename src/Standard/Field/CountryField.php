<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Standard\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;
use Psi\Component\ContentType\Standard\Storage as Storage;
use Psi\Component\ContentType\Standard\View as View;
use Symfony\Component\Form\Extension\Core\Type as Form;

class CountryField implements FieldInterface
{
    public function getViewType(): string
    {
        return View\ScalarType::class;
    }

    public function getFormType(): string
    {
        return Form\CountryType::class;
    }

    public function getStorageType(): string
    {
        return Storage\StringType::class;
    }

    public function configureOptions(FieldOptionsResolver $resolver)
    {
    }
}
