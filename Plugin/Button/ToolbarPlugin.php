<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Plugin\Button;

use Edifference\Sendy\Model\Config;
use Edifference\Sendy\Service\Shipment;
use Edifference\Sendy\Ui\Component\Button\ShippingLabelDataProvider;
use Exception;
use Magento\Backend\Block\Widget\Button\ButtonList;
use Magento\Backend\Block\Widget\Button\ToolbarInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * @copyright Copyright (c) eDifference 2024
 */
class ToolbarPlugin
{
    /** @var Config */
    private Config $config;
    /** @var ShippingLabelDataProvider */
    private ShippingLabelDataProvider $shippingLabelDataProvider;

    /**
     * @param Config                    $config
     * @param ShippingLabelDataProvider $shippingLabelDataProvider
     */
    public function __construct(
        Config                    $config,
        ShippingLabelDataProvider $shippingLabelDataProvider
    ) {
        $this->config = $config;
        $this->shippingLabelDataProvider = $shippingLabelDataProvider;
    }

    /**
     * Add the new sendy shipping button to the order page
     *
     * @param ToolbarInterface $subject
     * @param AbstractBlock    $context
     * @param ButtonList       $buttonList
     * @return void
     */
    public function beforePushButtons(
        ToolbarInterface $subject,
        AbstractBlock    $context,
        ButtonList       $buttonList
    ): void {
        $order = $this->getOrder(
            $context->getNameInLayout(),
            $context
        );
        if ($order === null) {
            return;
        }
        if ($this->shouldDisplayShippingButton($order) === false) {
            return;
        }
        $buttonList->add(
            'sendy_shipping',
            $this->shippingLabelDataProvider->getData($order),
            -1
        );
    }

    /**
     * Check if the button should be displayed
     *
     * @param OrderInterface $order
     * @return boolean
     */
    private function shouldDisplayShippingButton(OrderInterface $order): bool
    {
        try {
            if (!$this->config->isModuleEnabled()) {
                return false;
            }
            if (!$this->config->isConfigured()) {
                return false;
            }
            if (!empty($order->getData(Shipment::COLUMN_LABEL_UUID))) {
                // Do not show the button if there is already a label
                return false;
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Extract order data from context.
     *
     * @param string        $nameInLayout
     * @param AbstractBlock $context
     * @return OrderInterface|null
     */
    private function getOrder(
        string        $nameInLayout,
        AbstractBlock $context
    ): ?OrderInterface {
        switch ($nameInLayout) {
            case 'sales_order_edit':
                return $context->getOrder();
            case 'sales_invoice_view':
                return $context->getInvoice()->getOrder();
            case 'sales_shipment_view':
                return $context->getShipment()->getOrder();
            case 'sales_creditmemo_view':
                return $context->getCreditmemo()->getOrder();
        }
        return null;
    }
}
