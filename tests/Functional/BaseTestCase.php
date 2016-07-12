<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Component\ContentType\Tests\Functional;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    public function getContainer(array $config = [])
    {
        return new Container($config);
    }
}
