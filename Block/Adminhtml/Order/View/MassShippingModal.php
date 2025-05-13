<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Block\Adminhtml\Order\View;

/**
 * @copyright (c) eDifference 2024
 */
class MassShippingModal extends ShippingModal
{
    /**
     * Get the Form Submit URL
     *
     * @return string
     */
    public function getFormUrl(): string
    {
        return $this->urlBuilder->getUrl(
            'edifference_sendy/shipment/masscreate'
        );
    }
}
