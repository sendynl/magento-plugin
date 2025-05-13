<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Observer;

use Edifference\Sendy\Model\Config\Source\ProcessingMethod;
use Edifference\Sendy\Service\Webhook;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\NotFoundException;
use Sendy\Api\ApiException;

/**
 * @copyright (c) eDifference 2025
 */
class WebhookObserver implements ObserverInterface
{
    /**
     * @param Webhook $webhookService
     */
    public function __construct(
        private readonly Webhook $webhookService
    ) {
    }

    /**
     * Observer for admin_system_config_save
     *
     * @param Observer $observer
     * @return void
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ApiException
     */
    public function execute(Observer $observer)
    {
        /** @var array $configData */
        $configData = $observer->getEvent()->getData('configData');
        // Only handle sendy configuration
        if (empty($configData['section']) || $configData['section'] !== 'edifference_sendy') {
            return;
        }
        if (empty($configData['groups']['processing']['fields']['method']['value'])) {
            $this->webhookService->unregisterWebhook();
            return;
        }
        if ($configData['groups']['processing']['fields']['method']['value'] !== ProcessingMethod::SENDY) {
            $this->webhookService->unregisterWebhook();
            return;
        }
        $this->webhookService->registerWebhook();
    }
}
