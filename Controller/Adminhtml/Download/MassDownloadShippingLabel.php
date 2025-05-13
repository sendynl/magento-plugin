<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Controller\Adminhtml\Download;

use Edifference\Sendy\Service\Api;
use Edifference\Sendy\Service\Shipment;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Ui\Component\MassAction\Filter;

class MassDownloadShippingLabel extends AbstractShippingLabel
{
    /**
     * @param Context                  $context
     * @param OrderRepositoryInterface $orderRepository
     * @param Api                      $apiService
     * @param RawFactory               $resultRawFactory
     * @param Filter                   $filter
     * @param CollectionFactory        $collectionFactory
     * @param DateTime                 $dateTime
     */
    public function __construct(
        Context $context,
        OrderRepositoryInterface $orderRepository,
        Api $apiService,
        RawFactory $resultRawFactory,
        private readonly Filter $filter,
        private readonly CollectionFactory $collectionFactory,
        private readonly DateTime $dateTime
    ) {
        parent::__construct(
            $context,
            $orderRepository,
            $apiService,
            $resultRawFactory
        );
    }

    /**
     * Mass download the shipping labels
     *
     * @return ResponseInterface|ResultInterface
     * @throws GuzzleException
     */
    public function execute(): ResponseInterface|ResultInterface
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $uuids = [];
            foreach ($collection->getItems() as $order) {
                $uuids[] = $order->getData(Shipment::COLUMN_LABEL_UUID);
            }
            $uuids = array_filter(array_unique($uuids));
            if (count($uuids) === 0) {
                $this->messageManager->addErrorMessage(__('No shipping labels were found.'));
                return $this->_redirect('sales/order/index');
            }
            return $this->streamFile(
                sprintf('sendy_labels_%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
                current($this->apiService->getSendyConnection()->label->get(
                    $uuids
                ))
            );
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Unable to generate shipping label.'));
            return $this->_redirect('sales/order/index');
        }
    }
}
