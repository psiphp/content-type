<?php

namespace Psi\Component\ContentType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Psi\Component\ContentType\MappingInterface;
use Symfony\Component\Form\FormTypeInterface;
use Psi\Component\ContentType\ViewInterface;

/**
 * Field type.
 *
 * Field types encapsulate both backend and frontend behaviors of a type of
 * content, for example some text, an image, a geolocation, etc.
 */
interface FieldInterface
{
    /**
     * Return the view type.
     */
    public function getViewType(): ViewInterface;

    /**
     * Return the form type.
     */
    public function getFormType(): FormTypeInterface;

    /**
     * Return the field mapping.
     */
    public function getMapping(MappingBuilder $builder): MappingInterface;

    /**
     * Configure general options for this content field.
     */
    public function configureOptions(OptionsResolver $options);
}
