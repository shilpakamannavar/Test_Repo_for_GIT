<?php

namespace Auraine\BannerSlider\Model;


use Auraine\BannerSlider\Api\BannerRepositoryInterface;
use Auraine\BannerSlider\Api\Data\SliderInterface;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Model\AbstractModel;
use Auraine\BannerSlider\Model\ResourceModel\Slider as ResourceModel;
use Auraine\BannerSlider\Model\Resolver\DataProvider\Banner\ResourcePath as ResourcePathResolver;

class Slider extends AbstractModel implements SliderInterface
{

    /**
     * @var string
     */
    protected $_eventPrefix = 'auraine_bannerslider_slider';

    /**
     * @var string
     */
    protected $_eventObject = 'slider';

    /**
     * @var string
     */
    protected $_cacheTag = 'auraine_bannerslider_slider';

    /**
     * @var \Auraine\BannerSlider\Api\Data\BannerInterface[]|null
     */
    private $banners = null;

    /**
     * @var BannerRepositoryInterface
     */
    private $bannerRepository;
    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;
    /**
     * @var ResourcePathResolver
     */
    private $resourcePathResolver;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param BannerRepositoryInterface $bannerRepository
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param ResourcePathResolver $resourcePathResolver
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        BannerRepositoryInterface $bannerRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        ResourcePathResolver $resourcePathResolver,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->bannerRepository = $bannerRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->resourcePathResolver = $resourcePathResolver;
        $this->_init(ResourceModel::class);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getData('title');
    }

    /**
     * @param string $title
     * @return SliderInterface
     */
    public function setTitle(string $title): SliderInterface
    {
        return $this->setData('title', $title);
    }

    /**
     * @return int
     */
    public function getIsShowTitle(): int
    {
        return (int)$this->getData('is_show_title');
    }

    /**
     * @param int $isShowTitle
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function setIsShowTitle(int $isShowTitle): \Auraine\BannerSlider\Api\Data\SliderInterface
    {
        return $this->setData('is_show_title', $isShowTitle);
    }

    /**
     * @return int
     */
    public function getIsEnabled(): int
    {
        return (int)$this->getData('is_enabled');
    }

    /**
     * @param int $isEnabled
     * @return SliderInterface
     */
    public function setIsEnabled(int $isEnabled): SliderInterface
    {
        return $this->setData('is_enabled', $isEnabled);
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->getData('created_at');
    }

    /**
     * @param string $createdAt
     * @return SliderInterface
     */
    public function setCreatedAt(string $createdAt): SliderInterface
    {
        return $this->setData('created_at', $createdAt);
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->getData('updated_at');
    }

    /**
     * @param string $updatedAt
     * @return SliderInterface
     */
    public function setUpdatedAt(string $updatedAt): SliderInterface
    {
        return $this->setData('updated_at', $updatedAt);
    }

    /**
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface[]
     */
    public function getBanners(): array
    {
        if (!$this->banners) {
            $searchCriteria = $this->searchCriteriaBuilderFactory->create()
                ->addFilter('slider_id', $this->getEntityId(), 'eq')
                ->create();
            $banners = $this->bannerRepository->getList($searchCriteria)->getItems();
            foreach ($banners as $banner) {
                $banner->setResourcePath($this->resourcePathResolver->resolve($banner));
            }
            $this->banners = $banners;
        }
        return $this->banners;
    }
}