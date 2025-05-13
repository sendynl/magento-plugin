<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Model\ResourceModel\Event;

use Edifference\Sendy\Model\Event as EventModel;
use Edifference\Sendy\Model\ResourceModel\Event as EventResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Init the collection
     */
    protected function _construct()
    {
        $this->_init(EventModel::class, EventResourceModel::class);
    }
}
