<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Controller\Adminhtml\Oauth;

use Edifference\Sendy\Model\Config;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Cache\Type\Config as CacheConfig;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\NotFoundException;
use Sendy\Api\Connection;

/**
 * @copyright (c) eDifference 2024
 */
class Callback extends Action
{
    /**
     * @param Context           $context
     * @param WriterInterface   $configWriter
     * @param TypeListInterface $cacheTypeList
     * @param RedirectFactory   $redirectFactory
     * @param Config            $config
     * @param Connection        $sendyConnection
     */
    public function __construct(
        Context $context,
        private readonly WriterInterface   $configWriter,
        private readonly TypeListInterface $cacheTypeList,
        private readonly RedirectFactory   $redirectFactory,
        private readonly Config            $config,
        private readonly Connection        $sendyConnection
    ) {
        parent::__construct($context);
        // This function is allowed to be called without a key, as it is called by sendy
        $this->_publicActions = ['callback'];
    }

    /**
     * Callback function that saves the information for the oauth settings in the config and redirects to admin config
     *
     * @return Redirect
     * @throws NotFoundException
     */
    public function execute()
    {
        $this->configWriter->save(Config::CONFIG_PATH_AUTH_CODE, $this->getRequest()->getParam('code'));
        $this->cacheTypeList->cleanType(CacheConfig::TYPE_IDENTIFIER);

        $this->sendyConnection->setOauthClient(true)
            ->setClientId($this->config->getClientId())
            ->setClientSecret($this->config->getClientSecret())
            ->setRedirectUrl($this->getUrl('edifference_sendy/oauth/callback', ['key' => 'magento']))
            ->setAuthorizationCode($this->config->getAuthCode())
            ->setTokenUpdateCallback(function (Connection $connection) {
                $this->configWriter->save(Config::CONFIG_PATH_ACCESS_TOKEN, $connection->getAccessToken());
                $this->configWriter->save(Config::CONFIG_PATH_REFRESH_TOKEN, $connection->getRefreshToken());
                $this->configWriter->save(Config::CONFIG_PATH_TOKEN_EXPIRES, $connection->getTokenExpires());
                $this->cacheTypeList->cleanType(CacheConfig::TYPE_IDENTIFIER);
            });
        $this->sendyConnection->checkOrAcquireAccessToken();

        $result = $this->redirectFactory->create();
        $result->setPath('adminhtml/system_config/edit/section/edifference_sendy');
        return $result;
    }
}
