<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Model;

use Magento\Backend\Model\UrlInterface as BackendUrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\UrlInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Information;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @copyright (c) eDifference 2024
 */
class Config
{
    public const CONFIG_PATH_MODULE_ENABLED = 'edifference_sendy/general/enable';
    public const CONFIG_PATH_SENDY_SHOP = 'edifference_sendy/shipping_labels/sendy_shop';
    public const CONFIG_PATH_IMPORT_WEIGHT = 'edifference_sendy/import/import_weight';
    public const CONFIG_PATH_SHOP_URL = 'edifference_sendy/shop_url';
    public const CONFIG_PATH_CLIENT_ID = 'edifference_sendy/client_id';
    public const CONFIG_PATH_SECRET = 'edifference_sendy/secret';
    public const CONFIG_PATH_AUTH_CODE = 'edifference_sendy/auth_code';
    public const CONFIG_PATH_ACCESS_TOKEN = 'edifference_sendy/access_token';
    public const CONFIG_PATH_REFRESH_TOKEN = 'edifference_sendy/refresh_token';
    public const CONFIG_PATH_TOKEN_EXPIRES = 'edifference_sendy/token_expires';
    public const CONFIG_PATH_ORDER_STATUS_UPDATE_ENABLED = 'edifference_sendy/order_status/enable';
    public const CONFIG_PATH_ORDER_STATUS = 'edifference_sendy/order_status/order_status';
    public const CONFIG_PATH_PROCESSING_METHOD = 'edifference_sendy/processing/method';
    public const CONFIG_PATH_PROCESSING_ORDER_STATUS = 'edifference_sendy/processing/order_status';
    public const CONFIG_PATH_WEBHOOK_ID = 'edifference_sendy/processing/webhook_id';

    /**
     * @param ScopeConfigInterface  $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param UrlInterface          $urlBuilder
     * @param BackendUrlInterface   $backendUrl
     */
    public function __construct(
        private readonly ScopeConfigInterface  $scopeConfig,
        private readonly StoreManagerInterface $storeManager,
        private readonly UrlInterface $urlBuilder,
        private readonly BackendUrlInterface $backendUrl
    ) {
    }

    /**
     * Is the module enabled
     *
     * @param integer|string|StoreInterface|null $store
     * @return boolean
     */
    public function isModuleEnabled(StoreInterface|int|string $store = null): bool
    {
        try {
            return $this->getValue(self::CONFIG_PATH_MODULE_ENABLED, $store) === '1';
        } catch (NotFoundException $e) {
            return false;
        }
    }

    /**
     * Check if all settings required for a connection are set/configured
     *
     * @return boolean
     */
    public function isConfigured(): bool
    {
        try {
            $this->getShopUrl();
            if (!$this->isCorrectShopUrl()) {
                return false;
            }
            $this->getClientId();
            $this->getClientSecret();
            $this->getAuthCode();
            $this->getAccessToken();
            $this->getRefreshToken();
            $this->getTokenExpires();
            return true;
        } catch (NotFoundException $e) {
            return false;
        }
    }

    /**
     * Is the saved shop url equal to the correct admin domain url
     *
     * @return boolean
     */
    public function isCorrectShopUrl(): bool
    {
        try {
            return $this->getShopUrl() === $this->backendUrl->getBaseUrl();
        } catch (NotFoundException $e) {
            return false;
        }
    }

    /**
     * Is import weight enabled
     *
     * @param integer|string|StoreInterface|null $store
     * @return boolean
     * @throws NotFoundException
     */
    public function isImportWeightEnabled(StoreInterface|int|string $store = null): bool
    {
        return $this->getValue(self::CONFIG_PATH_IMPORT_WEIGHT, $store) === '1';
    }

    /**
     * Get saved shop url for the oath connection
     *
     * @return string
     * @throws NotFoundException
     */
    public function getShopUrl(): string
    {
        return $this->getValue(self::CONFIG_PATH_SHOP_URL);
    }

    /**
     * Client ID as UUID for the oath connection
     *
     * @return string
     * @throws NotFoundException
     */
    public function getClientId(): string
    {
        return $this->getValue(self::CONFIG_PATH_CLIENT_ID);
    }

    /**
     * Client secret for the oath connection
     *
     * @return string
     * @throws NotFoundException
     */
    public function getClientSecret(): string
    {
        return $this->getValue(self::CONFIG_PATH_SECRET);
    }

    /**
     * Oauth authorization code to request new token
     *
     * @return string
     * @throws NotFoundException
     */
    public function getAuthCode(): string
    {
        return $this->getValue(self::CONFIG_PATH_AUTH_CODE);
    }

    /**
     * Oauth access token
     *
     * @return string
     * @throws NotFoundException
     */
    public function getAccessToken(): string
    {
        return $this->getValue(self::CONFIG_PATH_ACCESS_TOKEN);
    }

    /**
     * Oauth refresh token
     *
     * @return string
     * @throws NotFoundException
     */
    public function getRefreshToken(): string
    {
        return $this->getValue(self::CONFIG_PATH_REFRESH_TOKEN);
    }

    /**
     * Oauth token expires timestamp
     *
     * @return integer
     * @throws NotFoundException
     */
    public function getTokenExpires(): int
    {
        return (int)$this->getValue(self::CONFIG_PATH_TOKEN_EXPIRES);
    }

    /**
     * Get the Sendy Shop
     *
     * @param integer|string|StoreInterface|null $store
     * @return string
     * @throws NotFoundException
     */
    public function getSendyShop(StoreInterface|int|string $store = null): string
    {
        return $this->getValue(self::CONFIG_PATH_SENDY_SHOP, $store) ?? '';
    }

    /**
     * Get processing method
     *
     * @param integer|string|StoreInterface|null $store
     * @return string
     * @throws NotFoundException
     */
    public function getProcessingMethod(StoreInterface|int|string $store = null): string
    {
        return $this->getValue(self::CONFIG_PATH_PROCESSING_METHOD, $store) ?? '';
    }

    /**
     * Get processing order status
     *
     * @param integer|string|StoreInterface|null $store
     * @return string
     * @throws NotFoundException
     */
    public function getProcessingOrderStatus(StoreInterface|int|string $store = null): string
    {
        return $this->getValue(self::CONFIG_PATH_PROCESSING_ORDER_STATUS, $store) ?? '';
    }

    /**
     * Get the webhook id
     *
     * @param integer|string|StoreInterface|null $store
     * @return string
     * @throws NotFoundException
     */
    public function getWebhookId(StoreInterface|int|string $store = null): string
    {
        return $this->getValue(self::CONFIG_PATH_WEBHOOK_ID, $store) ?? '';
    }

    /**
     * Get the store name
     *
     * @return string
     */
    public function getStoreName(): string
    {
        try {
            return $this->getValue(Information::XML_PATH_STORE_INFO_NAME);
        } catch (NotFoundException $e) {
            return sprintf('Magento (%s)', $this->urlBuilder->getBaseUrl());
        }
    }

    /**
     * Is the order status update functionality enabled
     *
     * @param integer|string|StoreInterface|null $store
     * @return boolean
     */
    public function isOrderStatusUpdateEnabled(StoreInterface|int|string $store = null): bool
    {
        try {
            if (empty($this->getValue(self::CONFIG_PATH_ORDER_STATUS_UPDATE_ENABLED, $store))) {
                return false;
            }
            if (empty($this->getValue(self::CONFIG_PATH_ORDER_STATUS, $store))) {
                return false;
            }
            return true;
        } catch (NotFoundException $e) {
            return false;
        }
    }

    /**
     * Get the order status
     *
     * @param integer|string|StoreInterface|null $store
     * @return string
     * @throws NotFoundException
     */
    public function getOrderStatus(StoreInterface|int|string $store = null): string
    {
        return $this->getValue(self::CONFIG_PATH_ORDER_STATUS, $store) ?? '';
    }

    /**
     * Get the config value
     *
     * @param string                             $path
     * @param integer|string|StoreInterface|null $store
     * @return mixed
     * @throws NotFoundException
     */
    private function getValue(
        string $path,
        StoreInterface|int|string $store = null
    ): mixed {
        $value = $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $this->getStoreId($store)
        );
        if ($value !== null) {
            return $value;
        }
        throw new NotFoundException(
            __(
                'Config value not found by path: %1. Please check your configuration',
                $path
            )
        );
    }

    /**
     * Get store id
     *
     * @param integer|string|StoreInterface|null $store
     * @return integer|null
     */
    protected function getStoreId(StoreInterface|int|string|null $store): ?int
    {
        try {
            return (int)$this->storeManager->getStore($store)->getId();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }
}
