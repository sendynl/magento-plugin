<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Observer;

use Edifference\Sendy\Service\Pickuppoint;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddPickuppointToOrder implements ObserverInterface
{
    /**
     * Add the quote pickup point data to the order
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $quote = $observer->getEvent()->getQuote();
        $order = $observer->getEvent()->getOrder();
        if (!$quote->getData(Pickuppoint::PICKUPPOINT_COLUMN_NAME)) {
            return;
        }
        $order->setData(Pickuppoint::PICKUPPOINT_COLUMN_NAME, $quote->getData(Pickuppoint::PICKUPPOINT_COLUMN_NAME));
    }
}
