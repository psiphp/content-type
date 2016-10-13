<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Standard\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\FieldRegistry;
use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;
use Psi\Component\ContentType\Storage\ConfiguredType;
use Psi\Component\ContentType\Storage\TypeFactory;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CollectionField implements FieldInterface
{
    private $registry;

    public function __construct(FieldRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function getViewType(): string
    {
        return CollectionView::class;
    }

    public function getFormType(): string
    {
        return CollectionType::class;
    }

    public function getStorageType(TypeFactory $factory): ConfiguredType
    {
        return $factory->create('collection');
    }

    public function configureOptions(FieldOptionsResolver $options)
    {
        $options->setRequired([
            'field',
        ]);
        $options->setDefault('field_options', []);

        $options->setFormMapper(function ($options) {
            $field = $this->registry->get($options['field']);
            $resolver = new FieldOptionsResolver();
            $field->configureOptions($resolver);
            $options = $resolver->resolveFormOptions($options['field_options']);

            return [
                'entry_type' => $field->getFormType(),
                'entry_options' => $options,
                'allow_add' => true,
                'allow_delete' => true,
            ];
        });
    }
}
