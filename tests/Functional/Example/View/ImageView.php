<?php

namespace Psi\Component\ContentType\Tests\Functional\Example\View;

use Psi\Component\ContentType\View\View;
use Psi\Component\ContentType\View\ViewInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageView implements ViewInterface
{
    public function buildView(View $view, $data, array $options)
    {
        $view['width'] = $data->width;
        $view['height'] = $data->height;
        $view['mimetype'] = $data->mimetype;
        $view['path'] = $data->path;
        $view->setValue($data);
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefaults([
            'path' => null,
            'repository' => null,
        ]);
    }
}
