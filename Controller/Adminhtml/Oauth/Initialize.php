<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Controller\Adminhtml\Oauth;

use Edifference\Sendy\Model\Config;
use Edifference\Sendy\Service\Api;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Cache\Type\Config as CacheConfig;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Math\Random;
use Ramsey\Uuid\Uuid;
use UnexpectedValueException;

/**
 * @copyright (c) eDifference 2024
 */
class Initialize extends Action
{
    /**
     * @param Context           $context
     * @param WriterInterface   $configWriter
     * @param TypeListInterface $cacheTypeList
     * @param Random            $random
     * @param Config            $config
     * @param RedirectFactory   $redirectFactory
     */
    public function __construct(
        Context                            $context,
        private readonly WriterInterface   $configWriter,
        private readonly TypeListInterface $cacheTypeList,
        private readonly Random            $random,
        private readonly Config            $config,
        private readonly RedirectFactory   $redirectFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Create a new client a secret for the oauth configuration and redirect to sendy to ask for permissions
     *
     * @return Redirect
     * @throws LocalizedException
     * @throws NotFoundException
     */
    public function execute()
    {
        $this->configWriter->save(Config::CONFIG_PATH_MODULE_ENABLED, 1);
        try {
            $this->config->getShopUrl();
            $this->config->getClientId();
            $this->config->getClientSecret();
            if (!$this->config->isCorrectShopUrl()) {
                throw new UnexpectedValueException('URL has changed');
            }
        } catch (UnexpectedValueException|NotFoundException $e) {
            // Client and secret are not set yet, or the URL has changed so we reset the basic information
            $this->configWriter->save(Config::CONFIG_PATH_SHOP_URL, $this->_backendUrl->getBaseUrl());
            $this->configWriter->save(Config::CONFIG_PATH_CLIENT_ID, Uuid::uuid4()->toString());
            $this->configWriter->save(Config::CONFIG_PATH_SECRET, $this->random->getRandomString(40));
        }
        $this->cacheTypeList->cleanType(CacheConfig::TYPE_IDENTIFIER);

        $result = $this->redirectFactory->create();
        $result->setUrl(
            Api::BASE_URL. Api::INITIALIZE_PATH . '?' . http_build_query([
                'client_id' => $this->config->getClientId(),
                'client_secret' => $this->config->getClientSecret(),
                'redirect_uri' => $this->getUrl('edifference_sendy/oauth/callback', ['key' => 'magento']),
                'name' => $this->config->getStoreName(),
                'type' => 'magento',
            ])
        );
        return $result;
    }
}
