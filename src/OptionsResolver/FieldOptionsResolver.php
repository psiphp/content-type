<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\OptionsResolver;

use Psi\Component\ContentType\FieldOptions;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Option resolver that allows options to be designated as either for the
 * content-type form, or the content-type view.
 *
 * All options that are not assigned will be to both the view and the form.
 */
class FieldOptionsResolver extends OptionsResolver
{
    private $formMapper;
    private $viewMapper;
    private $storageMapper;

    /**
     * Set closure which will map the resolved field options to form options.
     */
    public function setFormMapper(\Closure $optionMapper)
    {
        $this->formMapper = $optionMapper;
    }

    /**
     * Set closure which will map the resolved field options to view options.
     */
    public function setViewMapper(\Closure $optionMapper)
    {
        $this->viewMapper = $optionMapper;
    }

    /**
     * Set closure which will map the resolved field options to storage options.
     */
    public function setStorageMapper(\Closure $optionMapper)
    {
        $this->storageMapper = $optionMapper;
    }

    public function resolveFormOptions(FieldOptions $options): array
    {
        return $this->resolveOptions($this->formMapper, $options->getSharedOptions(), $options->getFormOptions());
    }

    public function resolveViewOptions(FieldOptions $options): array
    {
        return $this->resolveOptions($this->viewMapper, $options->getSharedOptions(), $options->getViewOptions());
    }

    public function resolveStorageOptions(FieldOptions $options): array
    {
        return $this->resolveOptions($this->storageMapper, $options->getSharedOptions(), $options->getViewOptions());
    }

    private function resolveOptions($mapper, array $sharedOptions, array $typeOptions): array
    {
        // if no mapper was specified, then pass all of the type options
        // directly.
        if (!$mapper) {
            return $typeOptions;
        }

        // otherwise use the mapper callback, passing both type and shared
        // options.
        $options = $this->resolve($sharedOptions);

        return $mapper($typeOptions, $options);
    }
}
