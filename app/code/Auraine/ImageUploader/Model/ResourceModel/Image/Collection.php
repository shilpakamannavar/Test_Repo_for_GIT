<?php
namespace Auraine\ImageUploader\Model\ResourceModel\Image;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Auraine\ImageUploader\Model\Image;
use Auraine\ImageUploader\Model\ResourceModel\Image as ResourceModelImage;

class Collection extends AbstractCollection
{
    /**
     * Fuction construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Image::class, ResourceModelImage::class);
    }
}
