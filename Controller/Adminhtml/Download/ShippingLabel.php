<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Controller\Adminhtml\Download;

use Edifference\Sendy\Service\Shipment;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class ShippingLabel extends AbstractShippingLabel
{
    /**
     * Download the order shipping label
     *
     * @return ResponseInterface|ResultInterface
     * @throws GuzzleException
     */
    public function execute(): ResponseInterface|ResultInterface
    {
        $orderId = $this->getRequest()->getParam('order_id');
        if (!$orderId) {
            $this->messageManager->addErrorMessage(__('Order ID is missing.'));
            return $this->_redirect('sales/order/index');
        }
        try {
            $order = $this->orderRepository->get($orderId);
            $shippingLabels = $this->apiService->getSendyConnection()->label->get(
                [$order->getData(Shipment::COLUMN_LABEL_UUID)]
            );
            return $this->streamFile(
                sprintf('sendy_label_%s.pdf', $order->getIncrementId()),
                current($shippingLabels)
            );
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Unable to generate shipping label.'));
            return $this->_redirect('sales/order/view', ['order_id' => $orderId]);
        }
    }
}
