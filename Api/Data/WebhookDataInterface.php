<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Api\Data;

/**
 * Interface for the webhook data that is sent from sendy app
 */
interface WebhookDataInterface
{
    public const ID = "id";
    public const EVENT = "event";
    public const RESOURCE = "resource";

    /**
     * Get ID
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Set ID
     *
     * @param string $id
     * @return void
     */
    public function setId(string $id): void;

    /**
     * Get event
     *
     * @return string
     */
    public function getEvent(): string;

    /**
     * Set event
     *
     * @param string $event
     * @return void
     */
    public function setEvent(string $event): void;

    /**
     * Get resource
     *
     * @return string
     */
    public function getResource(): string;

    /**
     * Set resource
     *
     * @param string $resource
     * @return void
     */
    public function setResource(string $resource): void;
}
