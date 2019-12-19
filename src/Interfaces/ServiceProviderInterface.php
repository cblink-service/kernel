<?php
namespace Cblink\Service\Kennel\Interfaces;

use Cblink\Service\Kennel\ServiceContainer;

/**
 * Interface ServiceProviderInterface
 * @package Cblink\Service\Kennel\Interfaces
 */
interface ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param ServiceContainer $app A container instance
     */
    public function register(ServiceContainer $app);
}
