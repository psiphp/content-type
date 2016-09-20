<?php

namespace Psi\Component\ContentType;

use Symfony\Component\OptionsResolver\OptionsResolver;

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
     *
     * @return ViewInterface
     */
    public function getViewType();

    /**
     * Return the form type.
     *
     * @return FormType
     */
    public function getFormType();

    /**
     * Return the field mapping.
     *
     * @return MappingInterface
     */
    public function getMapping(MappingBuilder $builder);

    /**
     * Configure general options for this content field.
     *
     * @param OptionsResolver
     */
    public function configureOptions(OptionsResolver $options);
}
