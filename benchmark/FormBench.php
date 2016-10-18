<?php

namespace Psi\Component\ContentType\Benchmark;

use Psi\Component\ContentType\Tests\Functional\BaseTestCase;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Article;

/**
 * @BeforeMethods({"setUp"})
 * @Revs(500)
 * @Iterations(10)
 * @OutputTimeUnit("milliseconds", precision=2)
 */
class FormBench extends BaseTestCase
{
    private $formFactory;

    public function setUp()
    {
        $this->formFactory = $this->getContainer([
            'mapping' => [
                Article::class => [
                    'alias' => 'article',
                    'properties' => [
                        'title' => [
                            'type' => 'text',
                        ],
                        'image' => [
                            'type' => 'image',
                        ],
                        'slideshow' => [
                            'type' => 'collection',
                            'options' => [
                                'field_type' => 'image',
                            ],
                        ],
                    ],
                ],
            ],
        ])->get('symfony.form_factory');
    }

    /**
     * @Subject()
     */
    public function create_form()
    {
        $builder = $this->formFactory->createBuilder(Article::class);
        $builder->getForm();
    }

    /**
     * @Subject()
     */
    public function create_submit_form()
    {
        $builder = $this->formFactory->createBuilder(Article::class);
        $form = $builder->getForm();
        $imageData = [
            'height' => 100,
            'width' => 100,
            'mimetype' => 'image/jpeg',
            'path' => 'path/to/foo.png',
        ];

        $data = [
            'title' => 'Hello',
            'image' => $imageData,
        ];

        $form->submit($data);
    }
}
