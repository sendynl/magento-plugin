<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Cron;

use Edifference\Sendy\Model\Config;
use Edifference\Sendy\Service\ShippingMethod;

/**
 * @copyright (c) eDifference 2025
 */
class SyncShippingMethods
{
    /**
     * @param Config         $config
     * @param ShippingMethod $shippingMethodService
     */
    public function __construct(
        private readonly Config $config,
        private readonly ShippingMethod $shippingMethodService
    ) {
    }

    /**
     * Sync the shipping methods to sendy app
     */
    public function execute(): void
    {
        if ($this->config->getProcessingMethod() !== Config\Source\ProcessingMethod::SENDY) {
            return;
        }
        $this->shippingMethodService->sync();
    }
}
