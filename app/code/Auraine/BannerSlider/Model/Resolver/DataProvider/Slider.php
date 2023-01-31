<?php

namespace Auraine\BannerSlider\Model\Resolver\DataProvider;

use Auraine\BannerSlider\Api\Data\BannerInterface;
use Auraine\BannerSlider\Api\Data\SliderInterface;
use Auraine\BannerSlider\Api\SliderRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;

class Slider
{
    /**
     * @var SliderRepositoryInterface
     */
    private $sliderRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;


    /**
     * Slider constructor.
     *
     * @param SliderRepositoryInterface $sliderRepository
     */
    public function __construct(
        SliderRepositoryInterface $sliderRepository,
        StoreManagerInterface $storeManager,
    ) {
        $this->sliderRepository = $sliderRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @param $sliderId
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData($args)
    {
        $data = $result = [];
        $sliderId = $args['sliderId'] ?? null;
        $sliderType = $args['slider_type'] ?? null;
        $pageType = $args['page_type'] ?? null;
        $sortOrder = $args['sort_order'] ?? null;
        $category_id = $args['category_id'] ?? null;
        $category_uid = $args['category_uid'] ?? null;
        $collection = $this->sliderRepository->getCollection()->addFieldToFilter('is_enabled', 1);
        $decode = "base64_decode";
        
        if (!empty($category_uid)) {
            $collection->addFieldToFilter('category_id', $decode($category_uid));
        }

        if (!empty($category_id)) {
            $collection->addFieldToFilter('category_id', $category_id);
        }
        
        if (!empty($sliderId)) {
            $collection->addFieldToFilter('entity_id', $sliderId);
        }

        if (!empty($sliderType)) {
            $collection->addFieldToFilter('slider_type', $sliderType);
        }

        if (!empty($pageType)) {
            $collection->addFieldToFilter('page_type', $pageType);
        }
        
        if ($collection->getSize() > 0) {
            $collection->setOrder('sort_order', 'ASC');
            foreach ($collection as $slider) {
                $data = $this->extractData($slider, [
                    'slider_id' => 'entity_id',
                    'title',
                    'is_show_title',
                    'is_enabled',
                    'additional_information',
                    'link',
                    'product_ids',
                    'discover',
                    'category_id',
                    'product_banner',
                    'identifier',
                    'slider_type',
                    'page_type',
                    'target_type',
                    'sort_order'
                ]);
                $encode = "base64_encode";
                $data['category_uid'] = $encode($data['category_id']);
                $data['banners'] = $this->getBanners($slider);
                $result[] = $data;
            }
        }
        return $result;
    }

    /**
     * Get Banner Data
     *
     * @param SliderInterface $slider
     * @return array
     */
    protected function getBanners($slider)
    {
        $banners = [];
        foreach ($slider->getBanners() as $banner) {
            $bannerData = $this->extractData($banner, [
                'slider_id',
                'resource_type',
                'resource_path',
                'is_enabled',
                'title',
                'alt_text',
                'link',
                'additional_information',
                'sort_order'
            ]);
            $bannerData['resource_map'] = $this->getResourceMap($banner);
            $banners[] = $bannerData;
        }
        return $banners;
    }

    /**
     * Get Resource Map Data
     * @param BannerInterface $banner
     * @return array
     */
    protected function getResourceMap($banner)
    {
        $resourceMap = $banner->getResourceMap();
        return $this->extractData($resourceMap, [
            'title',
            'min_width',
            'max_width'
        ]);
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @param string[] $fields
     * @return array
     */
    protected function extractData($object, $fields)
    {
        $data = [];
        foreach ($fields as $key => $field) {
            
            if (is_numeric($key)) {
                $key = $field;
            }

            if ($key=== 'resource_path') {
                $data[$key] = $this->videoCheck($object->getData($field));
            } else {
                $data[$key] = $object->getData($field);
            }
            
        }
        return $data;
    }

    /**
     * checking for youtube video
     *
     * @param string $url
     * @return void
     */
    protected function videoCheck($url)
    {
        $currentStore = $this->storeManager->getStore();
        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
       
        if (strpos($url, 'youtube.com') > 0) {
            return $url;
        } else {
            return $mediaUrl.$url;
        }
    }
}
