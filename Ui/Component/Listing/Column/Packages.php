<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Ui\Component\Listing\Column;

use Edifference\Sendy\Model\Config;
use Edifference\Sendy\Service\Shipment;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * @copyright (c) eDifference 2025
 */
class Packages extends Column
{
    /**
     * @param Config             $config
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        private readonly Config $config,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
    }

    /**
     * Prepares the data source.
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (!$this->config->isConfigured()) {
            return $dataSource;
        }
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }
        foreach ($dataSource['data']['items'] as &$item) {
            if (empty($item['entity_id'])) {
                continue;
            }
            if (empty($item[Shipment::COLUMN_LABEL_UUID])) {
                continue;
            }
            if (!empty($item[Shipment::COLUMN_PACKAGES])) {
                $item[Shipment::COLUMN_PACKAGES] .= '<br>';
            }
            $item[Shipment::COLUMN_PACKAGES] .= sprintf(
                '<a href="%s" target="_blank" data-role="action" data-bind="click: false" ' .
                'onclick="event.stopPropagation();">%s</a>',
                $this->getSendyRedirectUrl((int)$item['entity_id']),
                __('Open in Sendy')
            );
        }
        return $dataSource;
    }

    /**
     * Get URL to order redirect
     *
     * @param int $orderId
     * @return string
     */
    private function getSendyRedirectUrl(int $orderId)
    {
        return $this->context->getUrl('edifference_sendy/shipment/redirect', ['order_id' => $orderId]);
    }
}
