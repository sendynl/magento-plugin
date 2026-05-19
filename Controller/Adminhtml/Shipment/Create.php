<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Controller\Adminhtml\Shipment;

use Edifference\Sendy\Model\Config;
use Edifference\Sendy\Service\Shipment;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Throwable;

class Create extends Action
{
    /**
     * @param Context                  $context
     * @param RedirectFactory          $redirectFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param Shipment                 $shipment
     * @param Config                   $config
     */
    public function __construct(
        Context                                     $context,
        private readonly RedirectFactory            $redirectFactory,
        private readonly OrderRepositoryInterface   $orderRepository,
        private readonly Shipment                   $shipment,
        private readonly Config                     $config
    ) {
        parent::__construct($context);
    }

    /**
     * Create the sendy shipment
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $autoDownload = false;
        try {
            $order = $this->orderRepository->get(
                $this->getRequest()->getParam('order_id')
            );
            $this->shipment->createShipment(
                $order,
                (int)$this->getRequest()->getParam('package_qty', 1),
                $this->getRequest()->getParam('preference')
            );
            $this->messageManager->addSuccessMessage(__('Shipment successfully created.'));
            $autoDownload = $this->config->isAutoDownloadEnabled();
        } catch (Throwable $e) {
            $this->messageManager->addErrorMessage(__('Failed to create shipment: %1', $e->getMessage()));
        }
        $result = $this->redirectFactory->create();
        $params = ['order_id' => $this->getRequest()->getParam('order_id')];
        if ($autoDownload) {
            $params['auto_download'] = true;
        }
        $result->setPath(
            'sales/order/view',
            $params
        );
        return $result;
    }
}
