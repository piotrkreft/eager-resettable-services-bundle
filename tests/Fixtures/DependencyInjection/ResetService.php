<?php

declare(strict_types=1);

namespace PK\Tests\EagerResettableServicesBundle\Fixtures\DependencyInjection;

class ResetService
{
    /**
     * @var bool
     */
    private $resetted = false;

    public function reset(): void
    {
        $this->resetted = true;
    }

    public function isResetted(): bool
    {
        return $this->resetted;
    }
}
