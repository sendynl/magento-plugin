<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Api\Data;

/**
 * Interface for the webhook result that is sent to sendy app
 */
interface WebhookResultInterface
{
    public const STATUS = "status";
    public const MESSAGE = "message";

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Set status
     *
     * @param string $status
     * @return void
     */
    public function setStatus(string $status): void;

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Set message
     *
     * @param string $message
     * @return void
     */
    public function setMessage(string $message): void;
}
