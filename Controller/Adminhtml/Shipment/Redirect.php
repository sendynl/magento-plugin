<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Controller\Adminhtml\Shipment;

use Edifference\Sendy\Service\Api;
use Edifference\Sendy\Service\Shipment;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect as RedirectResult;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Throwable;

class Redirect extends Action
{
    /**
     * @param Context                  $context
     * @param RedirectFactory          $redirectFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param Api                      $api
     */
    public function __construct(
        Context                                   $context,
        private readonly RedirectFactory          $redirectFactory,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly Api                      $api,
    ) {
        parent::__construct($context);
    }

    /**
     * Redirect to the sendy shipment
     *
     * @return RedirectResult
     */
    public function execute(): RedirectResult
    {
        $result = $this->redirectFactory->create();
        try {
            $order = $this->orderRepository->get(
                $this->getRequest()->getParam('order_id')
            );
            if (empty($order->getData(Shipment::COLUMN_LABEL_UUID))) {
                throw new \UnexpectedValueException('No Sendy UUID known for this order');
            }
            $shipment = $this->api->getSendyConnection()->shipment->get(
                $order->getData(Shipment::COLUMN_LABEL_UUID)
            );
            if (empty($shipment['url'])) {
                throw new \UnexpectedValueException('No Sendy URL provided by API');
            }
            $result->setUrl($shipment['url']);
            return $result;
        } catch (Throwable $e) {
            $this->messageManager->addErrorMessage(__('Failed to redirect to Sendy: %1', $e->getMessage()));
            $result->setPath('sales/order/index');
            return $result;
        }
    }
}
