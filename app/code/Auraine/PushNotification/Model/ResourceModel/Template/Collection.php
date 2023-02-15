<?php

declare(strict_types=1);

namespace Auraine\PushNotification\Model\ResourceModel\Template;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'template_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Auraine\PushNotification\Model\Template::class,
            \Auraine\PushNotification\Model\ResourceModel\Template::class
        );
    }
}

