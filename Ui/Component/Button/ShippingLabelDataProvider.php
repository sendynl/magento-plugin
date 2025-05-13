<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Ui\Component\Button;

use Edifference\Sendy\Block\Adminhtml\Order\View\ShippingModal;
use Magento\Framework\View\LayoutInterface;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * @copyright Copyright (c) eDifference 2024
 */
class ShippingLabelDataProvider
{
    /**
     * @param LayoutInterface $layout
     */
    public function __construct(
        private readonly LayoutInterface $layout
    ) {
    }

    /**
     * Get Button Data
     *
     * @param OrderInterface $order
     * @return array
     */
    public function getData(OrderInterface $order): array
    {
        return [
            'label' => __('Sendy create Label'),
            'class' => 'reset',
            'onclick' => '',
            'after_html' => $this->layout->createBlock(ShippingModal::class)
                ->setTemplate('Edifference_Sendy::order/view/ShippingModal.phtml')
                ->setData('order', $order)
                ->toHtml(),
        ];
    }
}
