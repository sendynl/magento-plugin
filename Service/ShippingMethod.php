<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Service;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Shipping\Model\Config\Source\Allmethods;
use Sendy\Api\ApiException;

/**
 * @copyright (c) eDifference 2025
 */
class ShippingMethod
{
    /**
     * @param Api        $api
     * @param Allmethods $shippingMethods
     */
    public function __construct(
        private readonly Api $api,
        private readonly Allmethods $shippingMethods
    ) {
    }

    /**
     * Sync the shipping methods to sendy
     *
     * @return void
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ApiException
     */
    public function sync(): void
    {
        $data = [];
        foreach ($this->shippingMethods->toOptionArray(true) as $carrier) {
            if (!is_array($carrier['value'])) {
                continue;
            }
            foreach ($carrier['value'] as $method) {
                $data[] = [
                    'external_id' => $method['value'],
                    'name' => $method['label'],
                ];
            }
        }
        $this->api->getSendyConnection()->put('/webshop_shipping_methods', [
            'shipping_methods' => $data,
        ]);
    }
}
