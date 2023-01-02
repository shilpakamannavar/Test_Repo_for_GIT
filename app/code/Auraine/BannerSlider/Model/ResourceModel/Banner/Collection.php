<?php

namespace Auraine\BannerSlider\Model\ResourceModel\Banner;

use Auraine\BannerSlider\Model\Banner as Model;
use Auraine\BannerSlider\Model\ResourceModel\Banner as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}