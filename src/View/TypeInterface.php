<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\View;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface TypeInterface
{
    public function configureOptions(OptionsResolver $options);

    public function createView(ViewFactory $factory, $data, array $options): ViewInterface;
}
