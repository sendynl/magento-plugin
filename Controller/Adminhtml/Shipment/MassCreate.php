<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Controller\Adminhtml\Shipment;

use Edifference\Sendy\Service\Shipment;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Ui\Component\MassAction\Filter;
use Throwable;

class MassCreate extends Action
{
    /**
     * @param Context           $context
     * @param Shipment          $shipment
     * @param Filter            $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context                            $context,
        private readonly Shipment          $shipment,
        private readonly Filter            $filter,
        private readonly CollectionFactory $collectionFactory,
    ) {
        parent::__construct($context);
    }

    /**
     * Mass create the sendy shipments
     *
     * @return ResponseInterface
     */
    public function execute(): ResponseInterface
    {
        try {
            $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter(Shipment::COLUMN_LABEL_UUID, ['null' => true]);
            $this->filter->getCollection($collection);
            $hasFailed = false;
            foreach ($collection->getItems() as $order) {
                /** @var OrderInterface $order */
                $this->createShipment($order) || $hasFailed = true;
            }
            if ($hasFailed) {
                $this->messageManager->addWarningMessage(
                    __('Not all shipments were created successfully.')
                );
                return $this->_redirect('sales/order/index');
            }
            $this->messageManager->addSuccessMessage(
                __('Shipments successfully created for orders.')
            );
        } catch (Throwable $e) {
            $this->messageManager->addErrorMessage(__('Failed to create shipments: %1', $e->getMessage()));
        }
        return $this->_redirect('sales/order/index');
    }

    /**
     * Create the Sendy shipment for an order
     *
     * @param OrderInterface $order
     * @return boolean
     */
    private function createShipment(OrderInterface $order): bool
    {
        try {
            $this->shipment->createShipment(
                $order,
                (int)$this->getRequest()->getParam('package_qty', 1),
                $this->getRequest()->getParam('preference')
            );
            return true;
        } catch (Throwable $e) {
            $this->messageManager->addErrorMessage(
                __(
                    'Failed to create shipment for order %1: %2',
                    $order->getIncrementId(),
                    $e->getMessage()
                )
            );
        }
        return false;
    }
}
