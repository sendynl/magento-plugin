<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Service;

use Edifference\Sendy\Model\Config;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Cache\Type\Config as CacheConfig;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Exception\NotFoundException;
use Sendy\Api\Connection;

/**
 * @copyright (c) eDifference 2024
 */
class Api
{
    /**
     * @see Connection::BASE_URL
     */
    public const BASE_URL = 'https://app.sendy.nl';
    public const INITIALIZE_PATH = '/plugin/initialize';

    /**
     * @param Config            $config
     * @param Connection        $sendyConnection
     * @param WriterInterface   $configWriter
     * @param TypeListInterface $cacheTypeList
     * @param UrlInterface      $backendUrl
     */
    public function __construct(
        private readonly Config            $config,
        private readonly Connection        $sendyConnection,
        private readonly WriterInterface   $configWriter,
        private readonly TypeListInterface $cacheTypeList,
        private readonly UrlInterface      $backendUrl
    ) {
    }

    /**
     * Get a configured Sendy Connection
     *
     * @return Connection
     * @throws NotFoundException
     */
    public function getSendyConnection(): Connection
    {
        if (!$this->config->isConfigured()) {
            throw new NotFoundException(__('Sendy connection is not fully configured.'));
        }
        $this->sendyConnection->setOauthClient(true)
            ->setClientId($this->config->getClientId())
            ->setClientSecret($this->config->getClientSecret())
            ->setRedirectUrl($this->backendUrl->getUrl('edifference_sendy/oauth/callback', ['key' => 'magento']))
            ->setAuthorizationCode($this->config->getAuthCode())
            ->setAccessToken($this->config->getAccessToken())
            ->setRefreshToken($this->config->getRefreshToken())
            ->setTokenExpires($this->config->getTokenExpires())
            ->setTokenUpdateCallback(function (Connection $connection) {
                $this->configWriter->save(Config::CONFIG_PATH_ACCESS_TOKEN, $connection->getAccessToken());
                $this->configWriter->save(Config::CONFIG_PATH_REFRESH_TOKEN, $connection->getRefreshToken());
                $this->configWriter->save(Config::CONFIG_PATH_TOKEN_EXPIRES, $connection->getTokenExpires());
                $this->cacheTypeList->cleanType(CacheConfig::TYPE_IDENTIFIER);
            });
        return $this->sendyConnection;
    }
}
