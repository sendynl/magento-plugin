<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Api;

use Edifference\Sendy\Api\Data\WebhookDataInterface;
use Edifference\Sendy\Api\Data\WebhookResultInterface;

interface WebhookInterface
{
    /**
     * Handle webhook event
     *
     * @param \Edifference\Sendy\Api\Data\WebhookDataInterface $data
     * @return \Edifference\Sendy\Api\Data\WebhookResultInterface
     */
    public function save(WebhookDataInterface $data): WebhookResultInterface;
}
