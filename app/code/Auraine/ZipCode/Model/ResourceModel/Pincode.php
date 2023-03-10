<?php
declare(strict_types=1);

namespace Auraine\ZipCode\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Pincode extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('pincode', 'pincode_id');
    }
}
