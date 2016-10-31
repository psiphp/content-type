<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Standard\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\FieldOptions;
use Psi\Component\ContentType\FieldRegistry;
use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;
use Psi\Component\ContentType\Standard\Storage\CollectionType;
use Psi\Component\ContentType\Standard\View as View;
use Symfony\Component\Form\Extension\Core\Type as Form;

class CollectionField implements FieldInterface
{
    private $registry;

    public function __construct(FieldRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function getViewType(): string
    {
        return View\CollectionType::class;
    }

    public function getFormType(): string
    {
        return Form\CollectionType::class;
    }

    public function getStorageType(): string
    {
        return CollectionType::class;
    }

    public function configureOptions(FieldOptionsResolver $options)
    {
        $options->setRequired([
            'field_type',
        ]);
        $options->setDefault('field_options', []);
        $options->setFormMapper(function (array $options, array $shared) {

            // default to allowing add / delete (contrary to the form types
            // default behavior).
            $options = array_merge([
                'allow_add' => true,
                'allow_delete' => true,
            ], $options);

            // resolve the form options for the colletion entry.
            $field = $this->registry->get($shared['field_type']);
            $resolver = new FieldOptionsResolver();
            $field->configureOptions($resolver);
            $entryOptions = $resolver->resolveFormOptions(FieldOptions::create($shared['field_options']));

            // do not allow entry_type or entry_options to be overridden.
            $options['entry_type'] = $field->getFormType();
            $options['entry_options'] = $entryOptions;

            return $options;
        });

        $options->setViewMapper(function ($options, $shared) {
            return array_merge($options, [
                'field_type' => $shared['field_type'],
                'field_options' => $shared['field_options'],
            ]);
        });
    }
}
