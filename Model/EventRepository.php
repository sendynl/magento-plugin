<?php
declare (strict_types = 1);

namespace Edifference\Sendy\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Edifference\Sendy\Api\Data\EventInterface;
use Edifference\Sendy\Api\EventRepositoryInterface;
use Edifference\Sendy\Api\Data\EventInterfaceFactory;
use Edifference\Sendy\Api\Data\EventSearchResultsInterface;
use Edifference\Sendy\Api\Data\EventSearchResultsInterfaceFactory;
use Edifference\Sendy\Model\ResourceModel\Event\Collection;
use Edifference\Sendy\Model\ResourceModel\Event\CollectionFactory as EventCollectionFactory;
use Exception;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;

class EventRepository implements EventRepositoryInterface
{
    /**
     * @param EventInterfaceFactory              $eventFactory
     * @param EventSearchResultsInterfaceFactory $searchResultsFactory
     * @param EventCollectionFactory             $eventCollectionFactory
     * @param EntityManager                      $entityManager
     * @param CollectionProcessorInterface       $collectionProcessor
     */
    public function __construct(
        private readonly EventInterfaceFactory              $eventFactory,
        private readonly EventSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly EventCollectionFactory             $eventCollectionFactory,
        private readonly EntityManager                      $entityManager,
        private readonly CollectionProcessorInterface       $collectionProcessor
    ) {
    }

    /**
     * Get by ID
     *
     * @param int $entityId
     * @return EventInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $entityId): EventInterface
    {
        $itemModel = $this->eventFactory->create();
        $item = $this->entityManager->load($itemModel, $entityId);
        if (!$item->getId()) {
            throw NoSuchEntityException::singleField('entity_id', $entityId);
        }
        return $item;
    }

    /**
     * Get List
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return EventSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->eventCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var EventSearchResultsInterface $searchResult */
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * Save event
     *
     * @param EventInterface $event
     * @return EventInterface
     * @throws CouldNotSaveException
     */
    public function save(EventInterface $event): EventInterface
    {
        try {
            $event->setUpdatedAt(date('Y-m-d H:i:s'));
            $this->entityManager->save($event);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the event: %1',
                $exception->getMessage()
            ));
        }
        return $event;
    }

    /**
     * Delete event
     *
     * @param EventInterface $event
     * @return EventInterface
     * @throws Exception
     */
    public function delete(EventInterface $event): EventInterface
    {
        $this->entityManager->delete($event);
        return $event;
    }

    /**
     * Delete event by ID
     *
     * @param int $entityId
     * @return EventInterface
     * @throws NoSuchEntityException
     * @throws Exception
     */
    public function deleteById(int $entityId): EventInterface
    {
        return $this->delete($this->getById($entityId));
    }
}
