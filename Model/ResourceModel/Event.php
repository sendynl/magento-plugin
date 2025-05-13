<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Model\ResourceModel;

use Edifference\Sendy\Api\Data\EventInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Event extends AbstractDb
{
    /**
     * Init the model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(
            EventInterface::TABLE_NAME,
            EventInterface::ENTITY_ID
        );
    }
}
