<?php

namespace Psi\Component\ContentType\Benchmark;

use Psi\Bridge\ContentType\Doctrine\Orm\Tests\Functional\OrmTestCase;

/**
 * @BeforeMethods({"setUp"})
 * @Revs(1)
 * @Iterations(10)
 * @OutputTimeUnit("milliseconds", precision=2)
 */
class DoctrineOrmBench extends OrmTestCase
{
    use DoctrineBenchTrait;

    public function setUp()
    {
        $container = $this->getBenchContainer();
        $this->objectManager = $container->get('doctrine.entity_manager');
        $this->initOrm($this->objectManager);
    }
}
