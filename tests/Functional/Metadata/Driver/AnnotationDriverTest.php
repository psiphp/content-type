<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Component\ContentType\Tests\Functional\Metadata\Driver;

use Symfony\Cmf\Component\ContentType\Tests\Functional\BaseTestCase;
use Symfony\Cmf\Component\ContentType\Tests\Functional\Metadata\Driver\Model\Article;
use Symfony\Cmf\Component\ContentType\Tests\Functional\Metadata\Driver\Model\ArticleNoMapping;

class AnnotationDriverTest extends BaseTestCase
{
    /**
     * It should load the metadata from class anntations.
     */
    public function testLoadMetadata()
    {
        $driver = $this->getContainer()->get('cmf_content_type.metadata.driver.annotation');
        $reflection = new \ReflectionClass(Article::class);
        $metadata = $driver->loadMetadataForClass($reflection);

        $this->assertNotNull($metadata);

        $properties = $metadata->getPropertyMetadata();

        $this->assertCount(3, $properties);
        $this->assertEquals('text', $properties['title']->getType());
        $this->assertEquals('markdown', $properties['body']->getType());
        $this->assertEquals('image-collection', $properties['slider']->getType());
        $this->assertEquals(['max' => 10], $properties['slider']->getOptions());
    }

    /**
     * It should return null if the model has no content type mappings.
     */
    public function testLoadMetadataNoMappings()
    {
        $driver = $this->getContainer()->get('cmf_content_type.metadata.driver.annotation');
        $reflection = new \ReflectionClass(ArticleNoMapping::class);
        $metadata = $driver->loadMetadataForClass($reflection);

        $this->assertNull($metadata);
    }
}
