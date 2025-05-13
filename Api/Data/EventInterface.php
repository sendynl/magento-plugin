<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Api\Data;

use Magento\Framework\Model\AbstractModel;

interface EventInterface
{
    /**
     * String constants for property names
     */
    public const TABLE_NAME = 'sendy_api_event';
    public const ENTITY_ID = "entity_id";
    public const REQUEST = "request";
    public const RESPONSE = "response";
    public const MESSAGE = "message";
    public const TRACE = "trace";
    public const STATUS = "status";
    public const CREATED_AT = "created_at";
    public const UPDATED_AT = "updated_at";

    public const STATUS_SUCCESS = 0;
    public const STATUS_ERROR = 1;
    public const STATUS_QUEUED = 2;

    /**
     * Getter for EntityId.
     *
     * @return integer|null
     */
    public function getEntityId(): ?int;

    /**
     * Setter for EntityId.
     *
     * @param integer $entityId
     * @return AbstractModel
     * @phpcs:disable Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
     */
    public function setEntityId($entityId): AbstractModel;

    /**
     * Getter for request.
     *
     * @return string|null
     */
    public function getRequest(): ?string;

    /**
     * Setter for Queue
     *
     * @param string|null $request
     * @return EventInterface
     */
    public function setRequest(?string $request): EventInterface;

    /**
     * Getter for response.
     *
     * @return string|null
     */
    public function getResponse(): ?string;

    /**
     * Setter for Queue
     *
     * @param string|null $response
     * @return EventInterface
     */
    public function setResponse(?string $response): EventInterface;

    /**
     * Getter for message
     *
     * @return string|null
     */
    public function getMessage(): ?string;

    /**
     * Setter for message
     *
     * @param string|null $trace
     * @return EventInterface
     */
    public function setMessage(?string $trace): EventInterface;

    /**
     * Getter for trace
     *
     * @return string|null
     */
    public function getTrace(): ?string;

    /**
     * Setter for trace
     *
     * @param string|null $trace
     * @return EventInterface
     */
    public function setTrace(?string $trace): EventInterface;

    /**
     * Getter for Status.
     *
     * @return integer|null
     */
    public function getStatus(): ?int;

    /**
     * Setter for Status.
     *
     * @param integer|null $status
     * @return EventInterface
     */
    public function setStatus(?int $status): EventInterface;

    /**
     * Getter for the Created At
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Setter for the Created At
     *
     * @param string $createdAt
     * @return EventInterface
     */
    public function setCreatedAt(string $createdAt): EventInterface;

    /**
     * Getter for the Updated At
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Setter for the Updated At
     *
     * @param string|null $updatedAt
     * @return EventInterface
     */
    public function setUpdatedAt(?string $updatedAt): EventInterface;
}
