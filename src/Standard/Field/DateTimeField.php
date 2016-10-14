<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Standard\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;
use Psi\Component\ContentType\Standard\View\ScalarView;
use Psi\Component\ContentType\Storage\ConfiguredType;
use Psi\Component\ContentType\Storage\TypeFactory;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class DateTimeField implements FieldInterface
{
    public function getViewType(): string
    {
        return ScalarView::class;
    }

    public function getFormType(): string
    {
        return DateTimeType::class;
    }

    public function getStorageType(TypeFactory $factory): ConfiguredType
    {
        return $factory->create('datetime');
    }

    public function configureOptions(FieldOptionsResolver $options)
    {
    }
}