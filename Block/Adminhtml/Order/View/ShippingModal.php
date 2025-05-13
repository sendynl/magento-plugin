<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Block\Adminhtml\Order\View;

use Edifference\Sendy\Service\Api;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\UrlInterface;
use Throwable;

/**
 * @copyright (c) eDifference 2024
 */
class ShippingModal extends Template
{
    /**
     * @param Context      $context
     * @param UrlInterface $urlBuilder
     * @param Api          $api
     * @param array        $data
     */
    public function __construct(
        Context                         $context,
        protected readonly UrlInterface $urlBuilder,
        private readonly Api            $api,
        array                           $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Get the Form URL
     *
     * @return string
     */
    public function getFormUrl(): string
    {
        return $this->urlBuilder->getUrl(
            'edifference_sendy/shipment/create',
            ['order_id' => $this->getData('order')->getEntityId()]
        );
    }

    /**
     * Get the preferences
     *
     * @return array
     */
    public function getPreferences(): array
    {
        try {
            return $this->api->getSendyConnection()->shippingPreference->list();
        } catch (Throwable $e) {
            return [];
        }
    }
}
