<?php

declare(strict_types=1);

namespace Psi\Component\ContentType;

use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;

/**
 * Field types encompases storage and backend/frontend of a content field. For
 * example some text, an image, a geolocation, etc.
 */
interface FieldInterface
{
    /**
     * Return the view type.
     */
    public function getViewType(): string;

    /**
     * Return the form type.
     */
    public function getFormType(): string;

    /**
     * Return the storage type.
     */
    public function getStorageType(): string;

    /**
     * Configure general options for this content field.
     */
    public function configureOptions(FieldOptionsResolver $options);
}
