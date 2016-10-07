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
     * Resolve the form options.
     *
     * @param array $options
     */
    public function resolveFormOptions(array $options = []): array
    {
        $options = $this->resolve($options);

        if (!$this->formMapper) {
            return [];
        }

        $mapper = $this->formMapper;

        return $mapper($options);
    }

    /**
     * Resolve the view options.
     *
     * @param array $options
     */
    public function resolveViewOptions(array $options = []): array
    {
        $options = $this->resolve($options);

        if (!$this->viewMapper) {
            return [];
        }

        $mapper = $this->viewMapper;

        return $mapper->call($this, $options);
    }
}
