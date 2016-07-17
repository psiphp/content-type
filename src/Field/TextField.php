<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Component\ContentType\Field;

use Symfony\Cmf\Component\ContentType\FieldInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextField implements FieldInterface
{
    public function getViewType()
    {
        return 'scalar';
    }

    public function getFormType()
    {
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $options)
    {
    }
}
