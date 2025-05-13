<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Model\Data;

use Edifference\Sendy\Api\Data\WebhookResultInterface;
use Magento\Framework\DataObject;

/**
 * @copyright (c) eDifference 2025
 */
class WebhookResult extends DataObject implements WebhookResultInterface
{
    /**
     * Get status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set status
     *
     * @param string $status
     * @return void
     */
    public function setStatus(string $status): void
    {
        $this->setData(
            self::STATUS,
            $status
        );
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->getData(self::MESSAGE);
    }

    /**
     * Set message
     *
     * @param string $message
     * @return void
     */
    public function setMessage(string $message): void
    {
        $this->setData(
            self::MESSAGE,
            $message
        );
    }
}
