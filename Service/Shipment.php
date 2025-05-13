<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Service;

use DateTime;
use Edifference\Sendy\Model\Config;
use Edifference\Sendy\Model\Data\Shipment as ShipmentData;
use Edifference\Sendy\Model\Data\ShipmentFactory;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\ShipmentInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Status;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory;
use Sendy\Api\ApiException;
use UnexpectedValueException;
use Magento\Sales\Model\Order\Shipment\TrackFactory;
use Magento\Sales\Model\Order;
use Magento\Sales\Api\ShipmentRepositoryInterface;
use Magento\Sales\Model\Convert\Order as OrderConverter;
use Magento\Framework\Exception\LocalizedException;

/**
 * @copyright (c) eDifference 2024
 */
class Shipment
{
    public const COLUMN_LABEL_UUID = 'sendy_label_uuid';

    public const MINIMUM_WEIGHT = 0.1;

    /**
     * @param Api                          $apiService
     * @param ShipmentFactory              $shipmentFactory
     * @param Config                       $config
     * @param OrderRepositoryInterface     $orderRepository
     * @param TrackFactory                 $trackFactory
     * @param ShipmentRepositoryInterface  $shipmentRepository
     * @param OrderConverter               $orderConverter
     * @param TimezoneInterface            $timezone
     * @param CollectionFactory            $orderStatusCollectionFactory
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     */
    public function __construct(
        private readonly Api                          $apiService,
        private readonly ShipmentFactory              $shipmentFactory,
        private readonly Config                       $config,
        private readonly OrderRepositoryInterface     $orderRepository,
        private readonly TrackFactory                 $trackFactory,
        private readonly ShipmentRepositoryInterface  $shipmentRepository,
        private readonly OrderConverter               $orderConverter,
        private readonly TimezoneInterface            $timezone,
        private readonly CollectionFactory            $orderStatusCollectionFactory,
        private readonly SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    ) {
    }

    /**
     * Create a Sendy Shipment
     *
     * @param OrderInterface $order
     * @param int            $packageQty
     * @param string         $shippingPreference
     * @return array
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ApiException
     */
    public function createShipment(
        OrderInterface $order,
        int $packageQty,
        string $shippingPreference
    ): array {
        $shipmentData = $this->apiService->getSendyConnection()->shipment->createFromPreference(
            $this->getShipmentRequestData($order, $packageQty, $shippingPreference)->toArray()
        );
        if (!is_array($shipmentData) || empty($shipmentData['uuid'])) {
            throw new UnexpectedValueException('UUID not found in shipment data from Sendy');
        }
        $order->setData(self::COLUMN_LABEL_UUID, $shipmentData['uuid']);
        $this->orderRepository->save($order);
        $this->setOrderStatus($order);
        return $this->handleSendyShipment(
            $order,
            $shipmentData
        );
    }

    /**
     * Handle the sendy shipment data
     *
     * @param OrderInterface $order
     * @param array          $shipmentData
     * @return array
     * @throws LocalizedException
     * @throws NotFoundException
     */
    public function handleSendyShipment(
        OrderInterface $order,
        array          $shipmentData
    ): array {
        if ($this->isShippingComplete($order)) {
            $searchCriteria = $this->searchCriteriaBuilderFactory->create()->addFilter(
                'order_id',
                $order->getEntityId()
            );
            $shipments = $this->shipmentRepository->getList($searchCriteria->create());
            if ($shipments->getTotalCount() === 0) {
                throw new LocalizedException(
                    __("No shipment found for already shipped order.")
                );
            }
            $shipmentList = $shipments->getItems();
            // Add the tracking information to the last existing shipment of this order
            $this->addTrackingForShipment(
                end($shipmentList),
                $shipmentData
            );
            return $shipmentData;
        }
        $this->addTrackingForShipment(
            $this->createShipmentForOrder($order),
            $shipmentData
        );
        return $shipmentData;
    }

    /**
     * Create a shipment based on smart rules in sendy for processing the order in sendy
     *
     * @param OrderInterface $order
     * @return void
     * @throws ApiException
     * @throws GuzzleException
     * @throws NotFoundException
     */
    public function createSmartShipment(OrderInterface $order): void
    {
        $shipmentRequest = $this->getShipmentRequestData(
            $order,
            1
        );
        $shipmentRequest->setShippingMethodId($order->getShippingMethod());
        $shipmentData = $this->apiService->getSendyConnection()->shipment->createWithSmartRules(
            $shipmentRequest->toArray()
        );
        if (!is_array($shipmentData) || empty($shipmentData['uuid'])) {
            throw new UnexpectedValueException('UUID not found in shipment data from Sendy');
        }
        $order->setData(self::COLUMN_LABEL_UUID, $shipmentData['uuid']);
        $this->orderRepository->save($order);
    }

    /**
     * Get the shipment request data
     *
     * @param OrderInterface $order
     * @param int            $packageQty
     * @param string|null    $shippingPreference
     * @return ShipmentData
     * @throws NotFoundException
     */
    private function getShipmentRequestData(
        OrderInterface $order,
        int            $packageQty,
        ?string        $shippingPreference = null
    ): ShipmentData {
        $shippingAddress = $order->getShippingAddress();
        if (empty($shippingAddress)) {
            throw new NotFoundException(__('Shipping address not found.'));
        }
        $street = $shippingAddress->getStreet();
        if (empty($street)) {
            throw new NotFoundException(__('Street address not found.'));
        }
        /** @var ShipmentData $shipment */
        $shipment = $this->shipmentFactory->create();
        if ($shippingAddress->getCompany()) {
            $shipment->setCompanyName($shippingAddress->getCompany());
        }
        if (!empty($shippingPreference)) {
            $shipment->setPreferenceId($shippingPreference);
        }
        $shipment->setShopId($this->config->getSendyShop())
            ->setContact($order->getCustomerName())
            ->setEmail($order->getCustomerEmail())
            ->setPostalCode($shippingAddress->getPostcode())
            ->setCity($shippingAddress->getCity())
            ->setCountry($shippingAddress->getCountryId())
            ->setReference($order->getIncrementId())
            ->setWeight($this->getWeight($order))
            ->setAmount($packageQty)
            ->setOrderDate($this->timezone->date(new DateTime($order->getCreatedAt()))->format('c'));
        $this->setShipmentRequestAddressData(
            $shipment,
            $street
        );
        if ($order->getData(Pickuppoint::PICKUPPOINT_COLUMN_NAME)) {
            $shipment->setOptions([
                'parcel_shop_id' => $order->getData(Pickuppoint::PICKUPPOINT_COLUMN_NAME),
            ]);
        }
        return $shipment;
    }

    /**
     * Set the shipment request address data
     *
     * @param ShipmentData $shipment
     * @param array        $street
     * @return void
     */
    protected function setShipmentRequestAddressData(
        ShipmentData $shipment,
        array        $street
    ): void {
        $shipment->setStreet($street[0]);
        if (!array_key_exists(1, $street)) {
            $shipment->parseAndSetAddress($street[0]);
            return;
        }
        $shipment->setNumber($street[1]);
        if (!array_key_exists(2, $street)) {
            return;
        }
        $shipment->setAddition($street[2]);
        if (!array_key_exists(3, $street)) {
            return;
        }
        $shipment->setAddition($street[2] . ' ' . $street[3]);
    }

    /**
     * Get weight of the order
     *
     * @param OrderInterface $order
     * @return float
     * @throws NotFoundException
     */
    private function getWeight(OrderInterface $order): float
    {
        if (!$this->config->isImportWeightEnabled()) {
            return 1;
        }
        if ((float)$order->getWeight() < self::MINIMUM_WEIGHT) {
            return self::MINIMUM_WEIGHT;
        }
        return (float)$order->getWeight();
    }

    /**
     * Create a shipment for the order
     *
     * @param OrderInterface $order
     * @return ShipmentInterface
     * @throws LocalizedException
     */
    public function createShipmentForOrder(
        OrderInterface $order
    ): ShipmentInterface {
        /** @var Order $order */
        if (!$order->canShip()) {
            throw new LocalizedException(
                __("You can't create the Shipment of this order.")
            );
        }
        $orderShipment = $this->orderConverter->toShipment($order);
        foreach ($order->getAllItems() as $orderItem) {
            if (!$orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
                continue;
            }
            $qty = $orderItem->getQtyToShip();
            $shipmentItem = $this->orderConverter->itemToShipmentItem($orderItem)->setQty($qty);
            $orderShipment->addItem($shipmentItem);
        }
        $orderShipment->register();
        return $this->shipmentRepository->save($orderShipment);
    }

    /**
     * Add tracking data for shipment
     *
     * @param ShipmentInterface $shipment
     * @param array             $shipmentData
     * @return void
     */
    private function addTrackingForShipment(
        ShipmentInterface $shipment,
        array             $shipmentData
    ): void {
        if (!is_array($shipmentData['packages'])) {
            return;
        }
        foreach ($shipmentData['packages'] as $package) {
            $track = $this->trackFactory->create();
            $track->setCarrierCode($shipmentData['carrier_tag']);
            $track->setTitle($shipmentData['carrier']);
            $track->setTrackNumber($package['package_number']);
            $shipment->addTrack($track);
        }
        $this->shipmentRepository->save($shipment);
    }

    /**
     * Check if the order is shipped completely
     *
     * @param OrderInterface $order
     * @return boolean
     */
    private function isShippingComplete(OrderInterface $order): bool
    {
        /** @var Order $order */
        if ($order->canShip()) {
            return false;
        }
        foreach ($order->getItems() as $orderItem) {
            if ((float)$orderItem->getQtyOrdered() === (float)$orderItem->getQtyShipped()) {
                continue;
            }
            if ($orderItem->getParentItem()) {
                continue;
            }
            return false;
        }
        return true;
    }

    /**
     * Set the order status
     *
     * @param OrderInterface $order
     * @return void
     * @throws NotFoundException
     */
    public function setOrderStatus(OrderInterface $order): void
    {
        if (!$this->config->isOrderStatusUpdateEnabled()) {
            return;
        }
        /** @var Status $status */
        $status = $this->orderStatusCollectionFactory->create()
            ->joinStates()
            ->addFieldToFilter(
                'main_table.status',
                $this->config->getOrderStatus($order->getStoreId())
            )->getFirstItem();
        if (empty($status->getStatus())) {
            return;
        }
        $order->setStatus($status->getStatus());
        $order->setState($status->getData('state'));
        $this->orderRepository->save($order);
    }
}
