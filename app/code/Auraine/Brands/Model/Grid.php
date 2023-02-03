<?php

namespace Auraine\Brands\Model;

use Auraine\Brands\Api\Data\GridInterface;

class Grid extends \Magento\Framework\Model\AbstractModel implements GridInterface
{
    /**Cache tag for the brand
     *
     * @var CACHE_TAG
     */
    public const CACHE_TAG = 'auraine_shopbrand';
    /**Cache tag for the brands
     *
     * @var _cacheTag
     */
    protected $_cacheTag = 'auraine_shopbrand';
    /**
     * Event prefix for the brans
     *
     * @var _eventPrefix
     */
    protected $_eventPrefix = 'auraine_shopbrand';
    /**
     * Constructor for Grid
     *
     * Set EntityId.
     */
    protected function _construct()
    {
        $this->_init('Auraine\Brands\Model\ResourceModel\Grid');
    }
    /**
     * Set EntityId.
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }
    /**
     * Set entity id for brands
     *
     * @param int $entityId
     *
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
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }
    /**
     * Set Title.
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }
    /**
     * Set Title.
     *
     * @param int $status
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
    /**
     * Set Title.
     *
     * @param $description
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }
    /**
     * Set Title.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }
    /**
     * Set Title.
     */
    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }
    /**
     * Set Title.
     *
     * @param string $image
     */
    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }
    /**
     * Set Title.
     */
    public function getPopular()
    {
        return $this->getData(self::POPULAR);
    }
    /**
     * Set Title.
     *
     * @param int $is_popular
     */
    public function setPopular($is_popular)
    {
        return $this->setData(self::POPULAR, $is_popular);
    }
    /**
     * Set Title.
     */
    public function getJustin()
    {
        return $this->getData(self::JUSTIN);
    }
    /**
     * Set Title.
     *
     * @param int $is_justin
     */
    public function setJustin($is_justin)
    {
        return $this->getData(self::JUSTIN, $is_justin);
    }
    /**
     * Set Title.
     */
    public function getExclusive()
    {
        return $this->getData(self::EXCLUSIVE);
    }
    /**
     * Set is_exclusive.
     *
     * @param int is_exclusive
     */
    public function setExclusive($is_exclusive)
    {
        return $this->getData(self::EXCLUSIVE, $is_exclusive);
    }
    /**
     * Get is_featured.
     */
    public function getFeatured()
    {
        return $this->getData(self::FEATURED);
    }
    /**
     * Set is_featured.
     *
     * @param int $is_featured
     */
    public function setFeatured($is_featured)
    {
        return $this->getData(self::FEATURED, $is_featured);
    }
}
