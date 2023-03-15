<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Schedule\Model\ResourceModel\Schedule;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'schedule_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Auraine\Schedule\Model\Schedule::class,
            \Auraine\Schedule\Model\ResourceModel\Schedule::class
        );
    }
}

