<?php

namespace Auraine\BannerSlider\Model;

use Auraine\BannerSlider\Api\Data\ResourceMapInterface;
use Magento\Framework\Model\AbstractModel;
use Auraine\BannerSlider\Model\ResourceModel\ResourceMap as ResourceModel;

class ResourceMap extends AbstractModel implements ResourceMapInterface
{

    /**
     * @var string
     */
    protected $_eventPrefix = 'auraine_bannerslider_resource_map';

    /**
     * @var string
     */
    protected $_eventObject = 'resource_map';

    /**
     * @var string
     */
    protected $_cacheTag = 'auraine_bannerslider_resource_map';

   /**
    * BannerRepository Constructor
    *
    * @return void
    */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getData('title');
    }

    /**
     * Set Title
     *
     * @param string $title
     * @return ResourceMapInterface
     */
    public function setTitle(string $title): ResourceMapInterface
    {
        return $this->setData('title', $title);
    }

    /**
     * Get Min Width
     *
     * @return int|null
     */
    public function getMinWidth(): ?int
    {
        return $this->getData('min_width') ? (int)$this->getData('min_width') : null;
    }

    /**
     * Set Min Width
     *
     * @param int|null $minWidth
     * @return ResourceMapInterface
     */
    public function setMinWidth(?int $minWidth): ResourceMapInterface
    {
        return $this->setData('min_width', $minWidth);
    }

    /**
     * Get Max Width
     *
     * @return int
     */
    public function getMaxWidth(): ?int
    {
        return $this->getData('max_width') ? (int)$this->getData('max_width') : null;
    }

    /**
     * Set Max Width
     *
     * @param int|null $maxWidth
     * @return ResourceMapInterface
     */
    public function setMaxWidth(?int $maxWidth): ResourceMapInterface
    {
        return $this->setData('max_width', $maxWidth);
    }

    /**
     * Get Created At
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->getData('created_at');
    }

    /**
     * Set Created At
     *
     * @param string $createdAt
     * @return ResourceMapInterface
     */
    public function setCreatedAt(string $createdAt): ResourceMapInterface
    {
        return $this->setData('created_at', $createdAt);
    }

    /**
     * Get Updated At
     *
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->getData('updated_at');
    }

    /**
     * Set Updated At
     *
     * @param string $updatedAt
     * @return ResourceMapInterface
     */
    public function setUpdatedAt(string $updatedAt): ResourceMapInterface
    {
        return $this->setData('updated_at', $updatedAt);
    }
}
