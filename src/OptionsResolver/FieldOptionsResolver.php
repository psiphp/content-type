<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\OptionsResolver;

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

    public function resolveFormOptions(array $options = []): array
    {
        return $this->resolveOptions($this->formMapper, $options);
    }

    public function resolveViewOptions(array $options = []): array
    {
        return $this->resolveOptions($this->viewMapper, $options);
    }

    public function resolveStorageOptions(array $options = []): array
    {
        return $this->resolveOptions($this->storageMapper, $options);
    }

    private function resolveOptions($mapper, array $options): array
    {
        if (!$mapper) {
            return [];
        }

        $options = $this->resolve($options);

        return $mapper($options);
    }
}
