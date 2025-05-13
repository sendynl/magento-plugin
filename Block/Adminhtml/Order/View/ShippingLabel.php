<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Block\Adminhtml\Order\View;

use Edifference\Sendy\Model\Config;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Sales\Api\OrderRepositoryInterface;
use Edifference\Sendy\Service\Shipment;
use Magento\Framework\UrlInterface;

class ShippingLabel extends Template
{
    /**
     * @param Context                  $context
     * @param OrderRepositoryInterface $orderRepository
     * @param UrlInterface             $urlBuilder
     * @param Config                   $config
     * @param array                    $data
     */
    public function __construct(
        Context                                   $context,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly UrlInterface             $urlBuilder,
        private readonly Config                   $config,
        array                                     $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Check if order has a label
     *
     * @return bool
     */
    public function hasLabelUuid(): bool
    {
        if (!$this->getRequest()->getParam('order_id')) {
            return false;
        }
        if (!$this->config->isConfigured()) {
            return false;
        }
        $order = $this->orderRepository->get($this->getRequest()->getParam('order_id'));
        if ($order->getShipmentsCollection()->getTotalCount() === 0) {
            return false;
        }
        return (bool)$order->getData(
            Shipment::COLUMN_LABEL_UUID
        );
    }

    /**
     * Get the download URL
     *
     * @return string
     */
    public function getDownloadUrl(): string
    {
        return $this->urlBuilder->getUrl(
            'edifference_sendy/download/shippinglabel',
            ['order_id' => $this->getRequest()->getParam('order_id')]
        );
    }
}
