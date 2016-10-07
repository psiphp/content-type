<?php

namespace Psi\Component\ContentType\Tests\Functional\Metadata\Driver;

use Psi\Component\ContentType\Tests\Functional\BaseTestCase;
use Psi\Component\ContentType\Tests\Functional\Metadata\Driver\Model\Article;
use Psi\Component\ContentType\Tests\Functional\Metadata\Driver\Model\ArticleNoMapping;

class AnnotationDriverTest extends BaseTestCase
{
    /**
     * It should load the metadata from class anntations.
     */
    public function testLoadMetadata()
    {
        $driver = $this->getContainer()->get('psi_content_type.metadata.driver.annotation');
        $reflection = new \ReflectionClass(Article::class);
        $metadata = $driver->loadMetadataForClass($reflection);

        $this->assertNotNull($metadata);

        $properties = $metadata->getPropertyMetadata();

        $this->assertCount(3, $properties);
        $this->assertEquals('text', $properties['title']->getType());
        $this->assertEquals('title', $properties['title']->getRole());
        $this->assertEquals('markdown', $properties['body']->getType());
        $this->assertEquals('image-collection', $properties['slider']->getType());
        $this->assertEquals(['max' => 10], $properties['slider']->getOptions());
    }

    /**
     * It should return null if the model has no content type mappings.
     */
    public function testLoadMetadataNoMappings()
    {
        $driver = $this->getContainer()->get('psi_content_type.metadata.driver.annotation');
        $reflection = new \ReflectionClass(ArticleNoMapping::class);
        $metadata = $driver->loadMetadataForClass($reflection);

        $this->assertNull($metadata);
    }
}
