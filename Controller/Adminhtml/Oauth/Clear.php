<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Controller\Adminhtml\Oauth;

use Edifference\Sendy\Model\Config;
use Edifference\Sendy\Service\Webhook;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Cache\Type\Config as CacheConfig;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Throwable;

/**
 * @copyright (c) eDifference 2025
 */
class Clear extends Action
{
    /**
     * @param Context           $context
     * @param WriterInterface   $configWriter
     * @param TypeListInterface $cacheTypeList
     * @param RedirectFactory   $redirectFactory
     * @param Webhook           $webhookService
     */
    public function __construct(
        Context $context,
        private readonly WriterInterface   $configWriter,
        private readonly TypeListInterface $cacheTypeList,
        private readonly RedirectFactory   $redirectFactory,
        private readonly Webhook           $webhookService
    ) {
        parent::__construct($context);
    }

    /**
     * Clear the sendy connection settings
     *
     * @return Redirect
     */
    public function execute()
    {
        try {
            // try to remove the webhook first
            $this->webhookService->unregisterWebhook();
            // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock.DetectedCatch
        } catch (Throwable $e) {
            // Failed to remove the webhook, continue cleaning other config settings
        }
        $this->configWriter->delete(Config::CONFIG_PATH_AUTH_CODE);
        $this->configWriter->delete(Config::CONFIG_PATH_ACCESS_TOKEN);
        $this->configWriter->delete(Config::CONFIG_PATH_REFRESH_TOKEN);
        $this->configWriter->delete(Config::CONFIG_PATH_TOKEN_EXPIRES);
        $this->cacheTypeList->cleanType(CacheConfig::TYPE_IDENTIFIER);
        $result = $this->redirectFactory->create();
        $result->setPath('adminhtml/system_config/edit/section/edifference_sendy');
        return $result;
    }
}
