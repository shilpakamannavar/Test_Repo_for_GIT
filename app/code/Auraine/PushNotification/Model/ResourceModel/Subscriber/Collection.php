<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\PushNotification\Model\ResourceModel\Subscriber;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'subscriber_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Auraine\PushNotification\Model\Subscriber::class,
            \Auraine\PushNotification\Model\ResourceModel\Subscriber::class
        );
    }
}

