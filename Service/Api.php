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
    /** @var Config */
    private Config $config;
    /** @var Connection */
    private Connection $sendyConnection;
    /** @var WriterInterface */
    private WriterInterface $configWriter;
    /** @var TypeListInterface */
    private TypeListInterface $cacheTypeList;
    /** @var UrlInterface */
    private UrlInterface $backendUrl;

    /**
     * @param Config            $config
     * @param Connection        $sendyConnection
     * @param WriterInterface   $configWriter
     * @param TypeListInterface $cacheTypeList
     * @param UrlInterface      $backendUrl
     */
    public function __construct(
        Config            $config,
        Connection        $sendyConnection,
        WriterInterface   $configWriter,
        TypeListInterface $cacheTypeList,
        UrlInterface      $backendUrl
    ) {
        $this->config = $config;
        $this->sendyConnection = $sendyConnection;
        $this->configWriter = $configWriter;
        $this->cacheTypeList = $cacheTypeList;
        $this->backendUrl = $backendUrl;
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
