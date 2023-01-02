<?php

namespace Auraine\BannerSlider\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ResourceMap extends AbstractDb
{
    const MAIN_TABLE = 'auraine_bannerslider_resource_map';
    const ID_FIELD_NAME = 'entity_id';

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }
}