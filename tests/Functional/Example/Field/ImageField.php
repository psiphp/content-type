<?php

namespace Psi\Component\ContentType\Tests\Functional\Example\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\MappingBuilder;
use Psi\Component\ContentType\Tests\Functional\Example\Form\Type\ImageType;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Image;
use Psi\Component\ContentType\Tests\Functional\Example\View\ImageView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Psi\Component\ContentType\ViewInterface;
use Symfony\Component\Form\FormTypeInterface;
use Psi\Component\ContentType\MappingInterface;

class ImageField implements FieldInterface
{
    public function getViewType(): ViewInterface
    {
        return ImageView::class;
    }

    public function getFormType(): FormTypeInterface
    {
        return ImageType::class;
    }

    public function getMapping(MappingBuilder $builder): MappingInterface
    {
        return $builder->compound(Image::class)
          ->map('path', 'string', ['length' => 255])
          ->map('width', 'integer')
          ->map('height', 'integer')
          ->map('mimetype', 'string');
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('repository', 'default');
        $options->setDefault('path', '/');

        $options->setViewOptions(['repository', 'path']);
    }
}
