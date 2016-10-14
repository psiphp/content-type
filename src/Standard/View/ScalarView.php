<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Standard\View;

use Psi\Component\ContentType\View\View;
use Psi\Component\ContentType\View\ViewInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScalarView implements ViewInterface
{
    public function buildView(View $view, $data, array $options)
    {
        if (!is_scalar($data)) {
            throw new \InvalidArgumentException(sprintf(
                'Scalar view only accepts scalar values! Got "%s"',
                is_object($data) ? get_class($data) : gettype($data)
            ));
        }

        $view->setValue($data);
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('template', 'psi/scalar');
    }
}
