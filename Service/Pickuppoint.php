<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Service;

use Edifference\Sendy\Api\PickuppointInterface;
use Exception;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Quote\Api\CartRepositoryInterface;

class Pickuppoint implements PickuppointInterface
{
    public const PICKUPPOINT_COLUMN_NAME = 'sendy_pickuppoint';

    /**
     * @param JsonFactory             $jsonFactory
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(
        private readonly JsonFactory             $jsonFactory,
        private readonly CartRepositoryInterface $cartRepository
    ) {
    }

    /**
     * Save pickup point data to the quote
     *
     * @param string $cartId
     * @param string $id
     * @return Json
     */
    public function save(
        string $cartId,
        string $id
    ): Json {
        try {
            $cart = $this->cartRepository->getActive($cartId);
            $cart->setData(self::PICKUPPOINT_COLUMN_NAME, $id);
            $this->cartRepository->save($cart);
            $result = $this->jsonFactory->create();
            return $result->setData([
                'success' => true,
                'message' => __('Pickup point saved successfully'),
            ]);
        } catch (Exception $e) {
            return $result->setData([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
