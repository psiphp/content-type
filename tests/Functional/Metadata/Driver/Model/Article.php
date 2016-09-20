<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psi\Component\ContentType\Tests\Functional\Metadata\Driver\Model;

use Psi\Component\ContentType\Metadata\Annotations as CMFCT;

class Article
{
    /**
     * @CMFCT\Property(type="text")
     */
    public $title;

    /**
     * @CMFCT\Property(type="markdown")
     */
    public $body;

    /**
     * @CMFCT\Property(type="image-collection", options={ "max" = 10 })
     */
    public $slider;
}
