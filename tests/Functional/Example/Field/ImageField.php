<?php

namespace Psi\Component\ContentType\Tests\Functional\Example\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;
use Psi\Component\ContentType\Standard\Storage\ObjectType;
use Psi\Component\ContentType\Tests\Functional\Example\Form\Type as Form;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Image;
use Psi\Component\ContentType\Tests\Functional\Example\View\ImageType;

class ImageField implements FieldInterface
{
    public function getViewType(): string
    {
        return ImageType::class;
    }

    public function getFormType(): string
    {
        return Form\ImageType::class;
    }

    public function getStorageType(): string
    {
        return ObjectType::class;
    }

    public function configureOptions(FieldOptionsResolver $options)
    {
        $options->setDefault('repository', 'default');
        $options->setDefault('path', '/');

        $options->setViewMapper(function (array $options) {
            return [
                'repository' => $options['repository'],
                'path' => $options['path'],
            ];
        });

        $options->setStorageMapper(function (array $options) {
            return [
                'class' => Image::class,
            ];
        });
    }
}
