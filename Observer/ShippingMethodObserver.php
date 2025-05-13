<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Observer;

use Edifference\Sendy\Service\ShippingMethod;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * @copyright (c) eDifference 2025
 */
class ShippingMethodObserver implements ObserverInterface
{
    /**
     * @param ShippingMethod $shippingMethodService
     */
    public function __construct(
        private readonly ShippingMethod $shippingMethodService
    ) {
    }

    /**
     * Observer for admin_system_config_save
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var array $configData */
        $configData = $observer->getEvent()->getData('configData');
        // Only handle shipping methods configuration
        if (empty($configData['section']) || $configData['section'] !== 'carriers') {
            return;
        }
        $this->shippingMethodService->sync();
    }
}
