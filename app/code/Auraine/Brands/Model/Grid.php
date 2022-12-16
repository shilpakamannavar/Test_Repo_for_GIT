<?php

namespace Auraine\Brands\Model;

use Auraine\Brands\Api\Data\GridInterface;

class Grid extends \Magento\Framework\Model\AbstractModel implements GridInterface
{
    const CACHE_TAG = 'auraine_shopbrand';

    protected $_cacheTag = 'auraine_shopbrand';

    protected $_eventPrefix = 'auraine_shopbrand';

    protected function _construct()
    {
        $this->_init('Auraine\Brands\Model\ResourceModel\Grid');
    }

    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }
    /**
     * Set EntityId.
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }
    /**
     * Get Title.
     *
     * @return varchar
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }
    /**
     * Set Title.
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }


    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }

    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    public function getPopular()
    {
        return $this->getData(self::POPULAR);
    }

    public function setPopular($is_popular)
    {
        return $this->setData(self::POPULAR, $is_popular);
    }

    public function getJustin()
    {
        return $this->getData(self::JUSTIN);
    }

    public function setJustin($is_justin)
    {
        return $this->getData(self::JUSTIN,$is_justin);
    }
    
    public function getExclusive()
    {
        return $this->getData(self::EXCLUSIVE);
    }

    public function setExclusive($is_exclusive)
    {
        return $this->getData(self::EXCLUSIVE,$is_exclusive);
    }

    public function getFeatured()
    {
        return $this->getData(self::FEATURED);
    }

    public function setFeatured($is_featured)
    {
        return $this->getData(self::FEATURED,$is_featured);
    }
    
   
    
}
