<?php

namespace Auraine\BannerSlider\Model\ResourceModel\Slider;

use Auraine\BannerSlider\Model\Slider as Model;
use Auraine\BannerSlider\Model\ResourceModel\Slider as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
  /**
   * Checking Entity Id
   *
   * @var string
   */
    protected $_idFieldName = 'entity_id';

    /**
     * Create Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
