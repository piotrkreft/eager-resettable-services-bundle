<?php

declare(strict_types=1);

namespace PK\Tests\EagerResettableServicesBundle;

use PHPUnit\Framework\TestCase;
use PK\Tests\EagerResettableServicesBundle\Fixtures\Kernel;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class ServicesResetterFunctionalTest extends TestCase
{
    /**
     * @var Kernel
     */
    private $kernel;

    protected function setUp(): void
    {
        $this->kernel = new Kernel('test', false);
        $this->kernel->boot();
    }

    protected function tearDown(): void
    {
        (new Filesystem())->remove($this->kernel->getCacheDir());
        $this->kernel->shutdown();
    }

    public function testShouldResetTheServiceOnEveryButFirstRequest(): void
    {
        // given
        $request = new Request();

        // when
        $this->kernel->handle($request);
        // First kernel handle does not invoke resetters
        $this->kernel->handle($request);

        // then
        $this->assertTrue($this->kernel->getContainer()->get('pk_reset_service_eager')->isResetted());
        $this->assertFalse($this->kernel->getContainer()->get('pk_reset_service_lazy')->isResetted());
    }
}
