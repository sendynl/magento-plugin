<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Observer;

use Edifference\Sendy\Model\Config;
use Edifference\Sendy\Service\Shipment;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Sales\Api\Data\OrderInterface;
use Sendy\Api\ApiException;
use Throwable;

/**
 * @copyright (c) eDifference 2025
 */
class OrderStatusObserver implements ObserverInterface
{
    /**
     * @param Config   $config
     * @param Shipment $shipmentService
     */
    public function __construct(
        private readonly Config $config,
        private readonly Shipment $shipmentService
    ) {
    }

    /**
     * Observer for sales_order_save_after
     *
     * @param Observer $observer
     * @return void
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ApiException
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        if (!$order instanceof OrderInterface) {
            return;
        }

        try {
            if (!empty($order->getData(Shipment::COLUMN_LABEL_UUID))) {
                // Do check status when order already has a label
                return;
            }

            if ($this->config->getProcessingMethod() !== Config\Source\ProcessingMethod::SENDY) {
                // Only generate automatic orders when processing in sendy
                return;
            }

            if ($order->getStatus() != $this->config->getProcessingOrderStatus()) {
                // Not the correct status to generate the shipment
                return;
            }

            if (empty($order->getShippingMethod())) {
                // Order without shipment can be skipped
                return;
            }

            $this->shipmentService->createSmartShipment($order);
        } catch (NotFoundException $e) {
            // Not configured correctly yet, skip exception
            return;
        } catch (Throwable $e) {
            throw new LocalizedException(__('Failed to create shipments: %1', $e->getMessage()));
        }
    }
}
