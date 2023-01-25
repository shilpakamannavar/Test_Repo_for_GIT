<?php
declare(strict_types=1);

namespace Auraine\ZipCode\Model\ResourceModel\Pincode;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @var string $_idFieldName
     */
    protected $_idFieldName = 'pincode_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Auraine\ZipCode\Model\Pincode::class,
            \Auraine\ZipCode\Model\ResourceModel\Pincode::class
        );
    }
}
