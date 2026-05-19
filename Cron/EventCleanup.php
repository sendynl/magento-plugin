<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Cron;

use Edifference\Sendy\Api\Data\EventInterface;
use Edifference\Sendy\Api\EventRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @copyright Copyright (c) eDifference 2019
 */
class EventLogCleanup
{
    /** @var EventRepositoryInterface */
    private EventRepositoryInterface $eventRepository;
    /** @var SearchCriteriaBuilder */
    private SearchCriteriaBuilder $searchCriteria;
    /** @var FilterBuilder */
    private FilterBuilder $filterBuilder;
    /** @var FilterGroupBuilder */
    private FilterGroupBuilder $filterGroupBuilder;

    /**
     * @param EventRepositoryInterface $eventRepository
     * @param SearchCriteriaBuilder    $searchCriteria
     * @param FilterBuilder            $filterBuilder
     * @param FilterGroupBuilder       $filterGroupBuilder
     */
    public function __construct(
        EventRepositoryInterface $eventRepository,
        SearchCriteriaBuilder $searchCriteria,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder
    ) {
        $this->eventRepository = $eventRepository;
        $this->searchCriteria = $searchCriteria;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
    }

    /**
     * Cleanup the events older than 7 days
     *
     * @throws NoSuchEntityException
     */
    public function execute(): void
    {
        $this->searchCriteria->setFilterGroups([
            $this->filterGroupBuilder
                ->addFilter(
                    $this->filterBuilder->setField(EventInterface::UPDATED_AT)
                        ->setValue(date_create('-7 days')->format('Y-m-d H:i:s'))
                        ->setConditionType('lt')
                        ->create()
                )->create()
        ]);
        foreach ($this->eventRepository->getList($this->searchCriteria->create())->getItems() as $eventLog) {
            /** @var EventInterface $eventLog */
            $this->eventRepository->delete($eventLog);
        }
    }
}
