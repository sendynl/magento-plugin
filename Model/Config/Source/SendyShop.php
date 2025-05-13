<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Edifference\Sendy\Service\Api;
use Throwable;

class SendyShop implements OptionSourceInterface
{
    /**
     * @param Api $apiService
     */
    public function __construct(
        private readonly Api $apiService
    ) {
    }

    /**
     * Get the Sendy Shop options
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = [['label' => __('-- Please Select --'), 'value' => '']];
        try {
            $connection = $this->apiService->getSendyConnection();
            $shops = $connection->shop->list();
        } catch (Throwable $e) {
            return $options;
        }
        foreach ($shops as $shop) {
            $options[] = [
                'label' => $shop['name'],
                'value' => $shop['uuid']
            ];
        }
        return $options;
    }
}
