<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Model;

use Edifference\Sendy\Model\ResourceModel\Event as EventResourceModel;
use Edifference\Sendy\Api\Data\EventInterface;
use Magento\Framework\Model\AbstractModel;

class Event extends AbstractModel implements EventInterface
{
    /**
     * Init the model
     */
    protected function _construct()
    {
        $this->_init(EventResourceModel::class);
    }

    /**
     * Getter for EntityId.
     *
     * @return integer|null
     */
    public function getEntityId(): ?int
    {
        return (int)$this->getData(self::ENTITY_ID);
    }

    /**
     * Setter for EntityId.
     *
     * @param integer $entityId
     * @return AbstractModel
     * @phpcs:disable Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
     */
    public function setEntityId($entityId): AbstractModel
    {
        return $this->setData(
            self::ENTITY_ID,
            $entityId
        );
    }

    /**
     * Getter for request.
     *
     * @return string|null
     */
    public function getRequest(): ?string
    {
        return $this->getData(self::REQUEST);
    }

    /**
     * Setter for Queue
     *
     * @param string|null $request
     * @return EventInterface
     */
    public function setRequest(?string $request): EventInterface
    {
        return $this->setData(
            self::REQUEST,
            $request
        );
    }

    /**
     * Getter for response.
     *
     * @return string|null
     */
    public function getResponse(): ?string
    {
        return $this->getData(self::RESPONSE);
    }

    /**
     * Setter for Queue
     *
     * @param string|null $response
     * @return EventInterface
     */
    public function setResponse(?string $response): EventInterface
    {
        return $this->setData(
            self::RESPONSE,
            $response
        );
    }

    /**
     * Getter for message
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->getData(self::MESSAGE);
    }

    /**
     * Setter for message
     *
     * @param string|null $trace
     * @return EventInterface
     */
    public function setMessage(?string $trace): EventInterface
    {
        return $this->setData(
            self::MESSAGE,
            $trace
        );
    }

    /**
     * Getter for trace
     *
     * @return string|null
     */
    public function getTrace(): ?string
    {
        return $this->getData(self::TRACE);
    }

    /**
     * Setter for trace
     *
     * @param string|null $trace
     * @return EventInterface
     */
    public function setTrace(?string $trace): EventInterface
    {
        return $this->setData(
            self::TRACE,
            $trace
        );
    }

    /**
     * Getter for Status.
     *
     * @return integer|null
     */
    public function getStatus(): ?int
    {
        return (int)$this->getData(self::STATUS);
    }

    /**
     * Setter for Status.
     *
     * @param integer|null $status
     * @return EventInterface
     */
    public function setStatus(?int $status): EventInterface
    {
        return $this->setData(
            self::STATUS,
            $status
        );
    }

    /**
     * Getter for Created At
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Setter for Created At
     *
     * @param string $createdAt
     * @return EventInterface
     */
    public function setCreatedAt(string $createdAt): EventInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Getter for Updated At
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Setter for Updated At
     *
     * @param string|null $updatedAt
     * @return EventInterface
     */
    public function setUpdatedAt(?string $updatedAt): EventInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
