<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface EventSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Item list.
     *
     * @return EventSearchResultsInterface[]
     */
    public function getItems(): array;

    /**
     * Set Optimizers list.
     *
     * @param EventInterface[] $items
     * @return EventSearchResultsInterface
     */
    public function setItems(array $items): EventSearchResultsInterface;
}
