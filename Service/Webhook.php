<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Service;

use Edifference\Sendy\Api\Data\EventInterface;
use Edifference\Sendy\Api\Data\EventInterfaceFactory;
use Edifference\Sendy\Api\Data\WebhookDataInterface;
use Edifference\Sendy\Api\Data\WebhookResultInterface;
use Edifference\Sendy\Api\Data\WebhookResultInterfaceFactory;
use Edifference\Sendy\Api\EventRepositoryInterface;
use Edifference\Sendy\Api\WebhookInterface;
use Edifference\Sendy\Model\Config;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\App\Cache\Type\Config as CacheConfig;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\AuthorizationException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\UrlInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\ShipmentInterface;
use Magento\Sales\Api\Data\ShipmentTrackInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\ShipmentTrackRepositoryInterface;
use Sendy\Api\ApiException;
use Throwable;

/**
 * @copyright (c) eDifference 2025
 */
class Webhook implements WebhookInterface
{
    /**
     * @param Api                              $api
     * @param Config                           $config
     * @param UrlInterface                     $url
     * @param WriterInterface                  $configWriter
     * @param TypeListInterface                $cacheTypeList
     * @param WebhookResultInterfaceFactory    $resultFactory
     * @param RequestInterface                 $request
     * @param SearchCriteriaBuilderFactory     $searchCriteriaBuilderFactory
     * @param OrderRepositoryInterface         $orderRepository
     * @param Shipment                         $shipmentService
     * @param ShipmentTrackRepositoryInterface $shipmentTrackRepository
     * @param ShippingMethod                   $shippingMethodService
     * @param EventRepositoryInterface         $eventRepository
     * @param EventInterfaceFactory            $eventInterfaceFactory
     */
    public function __construct(
        private readonly Api $api,
        private readonly Config $config,
        private readonly UrlInterface $url,
        private readonly WriterInterface $configWriter,
        private readonly TypeListInterface $cacheTypeList,
        private readonly WebhookResultInterfaceFactory $resultFactory,
        private readonly RequestInterface $request,
        private readonly SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly Shipment $shipmentService,
        private readonly ShipmentTrackRepositoryInterface $shipmentTrackRepository,
        private readonly ShippingMethod $shippingMethodService,
        private readonly EventRepositoryInterface $eventRepository,
        private readonly EventInterfaceFactory $eventInterfaceFactory
    ) {
    }

    /**
     * Register the webhook in the sendy app
     *
     * @return void
     * @throws ApiException
     * @throws GuzzleException
     * @throws NotFoundException
     */
    public function registerWebhook(): void
    {
        try {
            $this->config->getWebhookId();
        } catch (NotFoundException $e) {
            $result = $this->api->getSendyConnection()->webhook->create([
                'url' => $this->url->getBaseUrl() . 'rest/V1/sendy/webhook',
                'events' => [
                    'shipment.generated',
                    'shipment.deleted',
                    'shipment.cancelled',
                    'shipment.delivered',
                ]
            ]);
            $this->configWriter->save(Config::CONFIG_PATH_WEBHOOK_ID, $result['id']);
            $this->cacheTypeList->cleanType(CacheConfig::TYPE_IDENTIFIER);
        } finally {
            $this->shippingMethodService->sync();
        }
    }

    /**
     * Unregister the webhook in the sendy app
     *
     * @return void
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ApiException
     */
    public function unregisterWebhook()
    {
        try {
            $id = $this->config->getWebhookId();
        } catch (NotFoundException $e) {
            // no webhook registered, return
            return;
        }
        $this->api->getSendyConnection()->webhook->delete($id);
        $this->configWriter->delete(Config::CONFIG_PATH_WEBHOOK_ID);
        $this->cacheTypeList->cleanType(CacheConfig::TYPE_IDENTIFIER);
    }

    /**
     * Handle webhook event
     *
     * @param WebhookDataInterface $data
     * @return WebhookResultInterface
     * @throws ApiException
     * @throws AuthorizationException
     * @throws GuzzleException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @throws NotFoundException
     * @throws Throwable
     */
    public function save(WebhookDataInterface $data): WebhookResultInterface
    {
        $event = $this->eventInterfaceFactory->create();
        $event->setRequest($this->request->getContent());
        try {
            if (empty($this->request->getHeader('X_SIGNATURE')) || empty($this->request->getHeader('X_TIMESTAMP'))) {
                throw new AuthorizationException(__('Invalid signature'));
            }

            if ($this->config->getProcessingMethod() !== Config\Source\ProcessingMethod::SENDY) {
                // We should not be receiving webhooks, call unregister functionality
                $this->unregisterWebhook();
                throw new LocalizedException(__('Webhook processing is disabled'));
            }

            switch ($data->getEvent()) {
                case 'shipment.generated':
                    $this->shipmentService->handleSendyShipment(
                        $this->getOrder($data->getId()),
                        $this->api->getSendyConnection()->shipment->get($data->getId())
                    );
                    break;
                case 'shipment.delivered':
                    $this->shipmentService->setOrderStatus(
                        $this->getOrder($data->getId())
                    );
                    break;
                case 'shipment.cancelled':
                case 'shipment.deleted':
                    $order = $this->getOrder($data->getId());
                    $order->setData(Shipment::COLUMN_LABEL_UUID);
                    $this->orderRepository->save($order);

                    // Remove tracking information from shipments
                    foreach ($order->getShipmentsCollection()->getItems() as $shipment) {
                        /** @var ShipmentInterface $shipment */
                        foreach ($shipment->getTracksCollection()->getItems() as $track) {
                            /** @var ShipmentTrackInterface $track */
                            $this->shipmentTrackRepository->delete($track);
                        }
                    }
                    break;
                default:
                    throw new LocalizedException(__('Unknown event received'));
            }
            $event->setResponse('success');
            $result = $this->resultFactory->create();
            return $result->setData([
                'status' => 'success',
                'message' => 'Webhook processed'
            ]);
        } catch (Throwable $e) {
            $event->setMessage($e->getMessage());
            $event->setTrace($e->getTraceAsString());
            $event->setStatus(EventInterface::STATUS_ERROR);
            throw $e;
        } finally {
            $this->eventRepository->save($event);
        }
    }

    /**
     * Get the order by shipment label UUID
     *
     * @param string $uuid
     * @return OrderInterface
     * @throws NoSuchEntityException
     */
    private function getOrder(string $uuid): OrderInterface
    {
        $searchCriteria = $this->searchCriteriaBuilderFactory->create()
            ->addFilter(Shipment::COLUMN_LABEL_UUID, $uuid);
        $orderList = $this->orderRepository->getList($searchCriteria->create());
        if ($orderList->getTotalCount() === 0) {
            throw NoSuchEntityException::singleField(Shipment::COLUMN_LABEL_UUID, $uuid);
        }
        return current($orderList->getItems());
    }
}
