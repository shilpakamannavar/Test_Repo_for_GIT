<?php
namespace Alternativetechlab\Mobilelogin\Model\ResourceModel\Otp;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Alternativetechlab\Mobilelogin\Model\Otp;
use Alternativetechlab\Mobilelogin\Model\ResourceModel\Otp as ResourceModel;

/**
 * Class Collection
 * Alternativetechlab\Mobilelogin\Model\ResourceModel\Otp
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Otp::class, ResourceModel::class);
    }
}
