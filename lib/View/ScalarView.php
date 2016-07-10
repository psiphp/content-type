<?php

namespace Symfony\Cmf\Component\ContentType\View;

use Symfony\Cmf\Component\ContentType\ViewInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Cmf\Component\ContentType\Metadata\PropertyMetadata;
use Symfony\Cmf\Component\ContentType\ContentView;

class ScalarView implements ViewInterface
{
    public function buildView(ContentViewBuilder $builder, ContentView $view, $data, array $options)
    {
        if (!is_scalar($data)) {
            throw new \InvalidArgumentException(sprintf(
                'Scalar view only accepts scalar values! Got "%s"',
                is_object($data) ? get_class($data) : gettype($data)
            ));
        }

        $view->setPrimaryValue($data);
    }
}
