<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * @copyright (c) eDifference 2025
 */
class ProcessingMethod implements OptionSourceInterface
{
    public const SENDY = 'sendy';
    public const MAGENTO = 'magento';

    /**
     * Get the Processing method options
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['label' => __('-- Please Select --'), 'value' => ''],
            ['label' => __('Create shipments in Magento'), 'value' => self::MAGENTO],
            ['label' => __('Process shipments in Sendy'), 'value' => self::SENDY],
        ];
    }
}
