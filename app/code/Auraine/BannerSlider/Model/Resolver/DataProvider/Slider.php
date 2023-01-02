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
     * @param SliderRepositoryInterface $sliderRepository
     */
    public function __construct(
        SliderRepositoryInterface $sliderRepository,
        StoreManagerInterface $storeManager
        )
    {
        $this->sliderRepository = $sliderRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @param $sliderId
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData($sliderId)
    {
        $data = [];
        $slider = $this->sliderRepository->loadById($sliderId);
        $data = $this->extractData($slider, [
            'slider_id' => 'entity_id',
            'title',
            'is_show_title',
            'is_enabled',
            'additional_information',
            'link',
            'product_ids',
            'additional_information',
            'discover',
            'product_banner'

        ]);
        $data['banners'] = $this->getBanners($slider);
        return $data;
    }

    /**
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
                'sort_order'
            ]);
            $bannerData['resource_map'] = $this->getResourceMap($banner);
            $banners[] = $bannerData;
        }
        return $banners;
    }

    /**
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
            if($key=== 'resource_path') {
                $currentStore = $this->storeManager->getStore();
                $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                $data[$key] = $mediaUrl.$object->getData($field);
            } else {
                $data[$key] = $object->getData($field);
            }
            
        }
        return $data;
    }
}