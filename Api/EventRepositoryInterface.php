<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Api;

use Edifference\Sendy\Api\Data\EventInterface;
use Edifference\Sendy\Api\Data\EventSearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\InputException;

interface EventRepositoryInterface
{
    /**
     * Get by ID
     *
     * @param integer $entityId
     * @return EventInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $entityId): EventInterface;

    /**
     * Get List
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return EventSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * Save the event
     *
     * @param EventInterface $event
     * @return EventInterface
     * @throws NoSuchEntityException
     */
    public function save(EventInterface $event): EventInterface;

    /**
     * Delete the event
     *
     * @param EventInterface $event
     * @return EventInterface
     * @throws NoSuchEntityException
     */
    public function delete(EventInterface $event): EventInterface;

    /**
     * Delete the event by ID
     *
     * @param integer $entityId
     * @return EventInterface
     * @throws NoSuchEntityException
     * @throws InputException
     */
    public function deleteById(int $entityId): EventInterface;
}
