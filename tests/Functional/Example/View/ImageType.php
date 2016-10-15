<?php

namespace Psi\Component\ContentType\Tests\Functional\Example\View;

use Psi\Component\ContentType\View\TypeInterface;
use Psi\Component\ContentType\View\ViewFactory;
use Psi\Component\ContentType\View\ViewInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType implements TypeInterface
{
    public function createView(ViewFactory $factory, $data, array $options): ViewInterface
    {
        return new ImageView($data);
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefaults([
            'path' => '/path',
            'repository' => 'foo',
            'template' => 'psi/image',
        ]);
    }
}
