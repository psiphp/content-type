<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Standard\View;

use Psi\Component\ContentType\View\TypeInterface;
use Psi\Component\ContentType\View\View;
use Psi\Component\ContentType\View\ViewFactory;
use Psi\Component\ContentType\View\ViewInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScalarType implements TypeInterface
{
    public function createView(ViewFactory $factory, $data, array $options): ViewInterface
    {
        if (null !== $data && !is_scalar($data)) {
            throw new \InvalidArgumentException(sprintf(
                'Scalar view only accepts scalar values! Got "%s"',
                is_object($data) ? get_class($data) : gettype($data)
            ));
        }

        return new ScalarView($options['template'], $data, $options['tag']);
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('template', 'psi/scalar');
        $options->setDefault('tag', null);
    }
}
