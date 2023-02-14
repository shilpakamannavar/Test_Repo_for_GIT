<?php

namespace Auraine\BannerSlider\Model;

use Auraine\BannerSlider\Api\Data\BannerInterface;
use Auraine\BannerSlider\Api\ResourceMapRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Auraine\BannerSlider\Model\ResourceModel\Banner as ResourceModel;

class Banner extends AbstractModel implements BannerInterface
{

    /**
     * @var string
     */
    protected $_eventPrefix = 'auraine_bannerslider_banner';

    /**
     * @var string
     */
    protected $_eventObject = 'banner';

    /**
     * @var string
     */
    protected $_cacheTag = 'auraine_bannerslider_banner';

    /**
     * @var ResourceMapRepositoryInterface
     */
    private $resourceMapRepository;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceMapRepositoryInterface $resourceMapRepository
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ResourceMapRepositoryInterface $resourceMapRepository,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->resourceMapRepository = $resourceMapRepository;
        $this->_init(ResourceModel::class);
    }

    /**
     * Get Slider Id
     *
     * @return string
     */
    public function getSliderId(): int
    {
        return (int)$this->getData('slider_id');
    }

    /**
     * Set Slider Id
     *
     * @param int $sliderId
     * @return BannerInterface
     */
    public function setSliderId(int $sliderId): BannerInterface
    {
        return $this->setData('slider_id', $sliderId);
    }

    /**
     * Get Resource Map Id
     *
     * @return int
     */
    public function getResourceMapId(): int
    {
        return $this->getData('resource_map_id');
    }

    /**
     * Set Resource Map Id
     *
     * @param int $resourceMapId
     * @return BannerInterface
     */
    public function setResourceMapId(int $resourceMapId): BannerInterface
    {
        return $this->setData('resource_map_id', $resourceMapId);
    }

    /**
     * Get Resource Type
     *
     * @return string|null
     */
    public function getResourceType(): ?string
    {
        return $this->getData('resource_type');
    }

    /**
     * Set Resource Type
     *
     * @param string|null $resourceType
     * @return BannerInterface
     */
    public function setResourceType(?string $resourceType): BannerInterface
    {
        return $this->setData('resource_type', $resourceType);
    }

    /**
     * Get Resource Path
     *
     * @return string|null
     */
    public function getResourcePath(): ?string
    {
        return $this->getData('resource_path');
    }

    /**
     * Set Resource Path
     *
     * @param string|null $resourcePathMobile
     * @return BannerInterface
     */
    public function setResourcePathMobile(?string $resourcePathMobile): BannerInterface
    {
        return $this->setData('resource_path_mobile', $resourcePathMobile);
    }

    /**
     * Get Resource Path
     *
     * @return string|null
     */
    public function getResourcePathMobile(): ?string
    {
        return $this->getData('resource_path_mobile');
    }

    /**
     * Set Resource Path
     *
     * @param string|null $resourcePath
     * @return BannerInterface
     */
    public function setResourcePath(?string $resourcePath): BannerInterface
    {
        return $this->setData('resource_path', $resourcePath);
    }

    /**
     * Get Is Enabled
     *
     * @return int
     */
    public function getIsEnabled(): int
    {
        return (int)$this->getData('is_enabled');
    }

    /**
     * Set Is Enabled
     *
     * @param int $isEnabled
     * @return BannerInterface
     */
    public function setIsEnabled(int $isEnabled): BannerInterface
    {
        return $this->setData('is_enabled', $isEnabled);
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
     * @return BannerInterface
     */
    public function setCreatedAt(string $createdAt): BannerInterface
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
     * @return BannerInterface
     */
    public function setUpdatedAt(string $updatedAt): BannerInterface
    {
        return $this->setData('updated_at', $updatedAt);
    }

    /**
     * Banner GetTitle
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getData('title');
    }

    /**
     * Banner SetcTitle
     *
     * @param string $title
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setTitle(string $title): \Auraine\BannerSlider\Api\Data\BannerInterface
    {
        return $this->setData('title', $title);
    }

    /**
     * Get Alt Text
     *
     * @return string
     */
    public function getAltText(): string
    {
        return $this->getData('alt_text');
    }

    /**
     * Set Alt Text
     *
     * @param string $altText
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setAltText(string $altText): \Auraine\BannerSlider\Api\Data\BannerInterface
    {
        return $this->setData('alt_text', $altText);
    }

    /**
     * Banner Get Link
     *
     * @return string
     */
    public function getLink(): string
    {
        return $this->getData('link');
    }

    /**
     * Banner Set Link
     *
     * @param string $link
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setLink(string $link): \Auraine\BannerSlider\Api\Data\BannerInterface
    {
        return $this->setData('link', $link);
    }

    /**
     * Banner Set Order
     *
     * @return int
     */
    public function getSortOrder(): int
    {
        return (int)$this->getData('sort_order');
    }

    /**
     * Banner Sort Order
     *
     * @param int $sortOrder
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setSortOrder(int $sortOrder): \Auraine\BannerSlider\Api\Data\BannerInterface
    {
        return $this->setData('sort_order', $sortOrder);
    }

    /**
     * Banner Resource Map
     *
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface|null
     */
    public function getResourceMap(): ?\Auraine\BannerSlider\Api\Data\ResourceMapInterface
    {
        try {
            return $this->resourceMapRepository->loadById($this->getResourceMapId());
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }
}
