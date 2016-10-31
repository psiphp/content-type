<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Standard\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;
use Psi\Component\ContentType\Standard\Storage\IntegerType;
use Psi\Component\ContentType\Standard\View\ScalarType;
use Symfony\Component\Form\Extension\Core\Type as Form;

class IntegerField implements FieldInterface
{
    public function getViewType(): string
    {
        return ScalarType::class;
    }

    public function getFormType(): string
    {
        return Form\IntegerType::class;
    }

    public function getStorageType(): string
    {
        return IntegerType::class;
    }

    public function configureOptions(FieldOptionsResolver $resolver)
    {
    }
}
