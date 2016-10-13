<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Standard\View;

use Psi\Component\ContentType\View\View;
use Psi\Component\ContentType\View\ViewBuilder;
use Psi\Component\ContentType\View\ViewInterface;
use Psi\Component\ContentType\View\ViewIterator;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionView implements ViewInterface
{
    private $builder;

    public function __construct(ViewBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function buildView(View $view, $data, array $options)
    {
        if (!is_array($data) && !$data instanceof \Traversable) {
            throw new \InvalidArgumentException(sprintf(
                'Data must be traversable or an array, got: "%s"',
                is_object($data) ? get_class($data) : gettype($data)
            ));
        }

        if (is_array($data)) {
            $data = new \ArrayIterator($data);
        }

        $view->setValue(new ViewIterator(
            $this->builder,
            $data,
            $options['field_type'],
            $options['field_options']
        ));
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('template', 'psi/collection');
        $options->setRequired([
            'field_type',
            'field_options',
        ]);
    }
}
