<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Plugin\Component;

use Edifference\Sendy\Model\Config;
use Magento\Ui\Component\MassAction;

/**
 * @copyright (c) eDifference 2025
 */
class MassActionPlugin
{
    /**
     * @param Config $config
     */
    public function __construct(
        private readonly Config $config,
    ) {
    }

    /**
     * Filter the Sendy mass actions if the module is not enabled or configured
     *
     * @param MassAction $subject
     * @param void $result
     * @return void
     */
    public function afterPrepare(
        MassAction $subject,
        $result
    ): void {
        if ($subject->getContext()->getNamespace() !== 'sales_order_grid') {
            return;
        }
        if ($this->config->isModuleEnabled() && $this->config->isConfigured()) {
            return;
        }

        $config = $subject->getData('config');
        foreach ($config['actions'] as $key => $action) {
            if (!isset($action['module'])) {
                continue;
            }
            if ($action['module'] !== 'Edifference_Sendy') {
                continue;
            }
            unset($config['actions'][$key]);
        }
        $subject->setData('config', $config);
    }
}
