<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Standard\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;
use Psi\Component\ContentType\Standard\View\ScalarType;
use Psi\Component\ContentType\Storage\ConfiguredType;
use Psi\Component\ContentType\Storage\TypeFactory;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TextField implements FieldInterface
{
    public function getViewType(): string
    {
        return ScalarType::class;
    }

    public function getFormType(): string
    {
        return TextType::class;
    }

    public function getStorageType(TypeFactory $factory): ConfiguredType
    {
        return $factory->create('string');
    }

    public function configureOptions(FieldOptionsResolver $options)
    {
        $options->setDefault('tag', null);
        $options->setViewMapper(function (array $options) {
            return [
                'tag' => $options['tag'],
            ];
        });
    }
}
