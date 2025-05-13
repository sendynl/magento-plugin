<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Model\Data;

use Edifference\Sendy\Api\Data\WebhookDataInterface;
use Magento\Framework\DataObject;

/**
 * @copyright (c) eDifference 2025
 */
class WebhookData extends DataObject implements WebhookDataInterface
{
    /**
     * Get ID
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->getData(self::ID);
    }

    /**
     * Set ID
     *
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
    {
        $this->setData(
            self::ID,
            $id
        );
    }

    /**
     * Get event
     *
     * @return string
     */
    public function getEvent(): string
    {
        return $this->getData(self::EVENT);
    }

    /**
     * Set event
     *
     * @param string $event
     * @return void
     */
    public function setEvent(string $event): void
    {
        $this->setData(
            self::EVENT,
            $event
        );
    }

    /**
     * Get resource
     *
     * @return string
     */
    public function getResource(): string
    {
        return $this->getData(self::RESOURCE);
    }

    /**
     * Set resource
     *
     * @param string $resource
     * @return void
     */
    public function setResource(string $resource): void
    {
        $this->setData(
            self::RESOURCE,
            $resource
        );
    }
}
