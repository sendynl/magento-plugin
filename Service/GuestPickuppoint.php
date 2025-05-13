<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Service;

use Edifference\Sendy\Api\GuestPickuppointInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Quote\Model\QuoteIdMaskFactory;

class GuestPickuppoint implements GuestPickuppointInterface
{
    /**
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param Pickuppoint        $pickuppoint
     */
    public function __construct(
        private readonly QuoteIdMaskFactory $quoteIdMaskFactory,
        private readonly Pickuppoint        $pickuppoint,
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
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->pickuppoint->save(
            $quoteIdMask->getQuoteId(),
            $id
        );
    }
}
