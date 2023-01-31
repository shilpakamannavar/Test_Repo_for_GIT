<?php
namespace Auraine\ImageUploader\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Image extends AbstractDb
{

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        return $this->_init('Auraine_images', 'image_id');
    }
}
