<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Staticcontent\Model\ResourceModel\Type;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'type_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Auraine\Staticcontent\Model\Type::class,
            \Auraine\Staticcontent\Model\ResourceModel\Type::class
        );
    }
}
