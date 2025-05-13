<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Api;

use Magento\Framework\Controller\Result\Json;

interface GuestPickuppointInterface
{
    /**
     * Save the pickup point
     *
     * @param string $cartId
     * @param string $id
     * @return Json
     */
    public function save(string $cartId, string $id): Json;
}
