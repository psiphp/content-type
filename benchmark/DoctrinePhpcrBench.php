<?php

namespace Psi\Component\ContentType\Benchmark;

use Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Tests\Functional\PhpcrOdmTestCase;

/**
 * @BeforeMethods({"setUp"})
 * @Revs(1)
 * @Iterations(10)
 * @OutputTimeUnit("milliseconds", precision=2)
 */
class DoctrinePhpcrBench extends PhpcrOdmTestCase
{
    use DoctrineBenchTrait;

    public function setUp()
    {
        $container = $this->getBenchContainer();
        $this->objectManager = $container->get('doctrine_phpcr.document_manager');
        $this->initPhpcr($this->objectManager);
    }
}
