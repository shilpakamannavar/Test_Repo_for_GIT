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
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        SliderRepositoryInterface $sliderRepository,
        StoreManagerInterface $storeManager,
    ) {
        $this->sliderRepository = $sliderRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * Get Data
     *
     * @param [type] $args
     * @return void
     */
    public function getData($args)
    {
        $data = $result = [];
        $sliderId = $args['sliderId'] ?? null;
        $sliderIds = $args['sliderIds'] ?? null;
        $sliderType = $args['slider_type'] ?? null;
        $pageType = $args['page_type'] ?? null;
        $categoryId = $args['category_id'] ?? null;
        $categoryUid = $args['category_uid'] ?? null;
        $type = $args['type'] ?? null;
        $collection = $this->sliderRepository->getCollection()->addFieldToFilter('is_enabled', 1);
        $decode = "base64_decode";

        if (!empty($type) && $type === 'mobile') {
            $collection->addFieldToFilter('display_type', ['neq' => 'web']);
        } else {
            $collection->addFieldToFilter('display_type', ['neq' => 'mobile']);
        }

        if (!empty($categoryUid)) {
            $collection->addFieldToFilter('category_id', $decode($categoryUid));
        }

        if (!empty($sliderId)) {
            $collection->addFieldToFilter('entity_id', $sliderId);
        }

        if (!empty($sliderIds)) {
            $collection->addFieldToFilter('entity_id', [
                'in' => [$sliderIds]]);
        }

        if (!empty($sliderType)) {
            $collection->addFieldToFilter('slider_type', $sliderType);
        }

        if (!empty($pageType)) {
            $collection->addFieldToFilter('page_type', $pageType);
        }
        if (!empty($categoryId)) {
            $collection->addFieldToFilter('category_id', $categoryId);
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
                    'target_id',
                    'sort_order',
                    'display_type'
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
            if ($banner->getIsEnabled()== 1) {
                $bannerData = $this->extractData($banner, [
                    'slider_id',
                    'resource_type',
                    'resource_path',
                    'resource_path_mobile',
                    'resource_path_poster',
                    'is_enabled',
                    'title',
                    'alt_text',
                    'link',
                    'additional_information',
                    'sort_order',
                    'slider_target_id',
                    'category_id',
                    'target_type',
                    'video_type',
                    'target_id',
                    'banner_id' => 'entity_id'
                ]);
                $encode = "base64_encode";
                $bannerData['category_uid'] = $encode($bannerData['category_id']);
                $communityId = explode(',', $bannerData['slider_target_id']);
                $communityId=str_replace('"', "", json_encode($communityId));
                $communityId = json_decode($communityId, true);
                $bannerData['slider_target_id'] = $communityId;
                $bannerData['resource_map'] = $this->getResourceMap($banner);
                $banners[] = $bannerData;
            }
        }
        return $banners;
    }

    /**
     * Get Resource Map Data
     *
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
     * Extract Data
     *
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
            if ($key === 'resource_path' || $key=== 'resource_path_mobile' || $key == 'resource_path_poster') {
                $data[$key] = $this->videoCheck($object->getData($field), $field);
                $x[$key] = $field;
            } else {
                $data[$key] = $object->getData($field);
            }

        }

        return $data;
    }

    /**
     * Checking for youtube video
     *
     * @param string $url
     * @return void
     */
    protected function videoCheck($url)
    {
        $output = explode(".", $url);
        $currentStore = $this->storeManager->getStore();
        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        if (strpos($url, 'youtube.com') > 0 || in_array("s3", $output)||  $output[count($output)-1] === 'mp4') {
            return $url;
        } else {
            return ($url) ? $mediaUrl.$url: '';
        }
    }
}
