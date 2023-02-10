<?php

namespace Auraine\BannerSlider\Ui\Component\Listing\Column\Banner\ResourcePathMobile;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class LocalImage implements ProcessorInterfaceMobile
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * LocalImage constructor.
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * Process method
     *
     * @param array $item
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function process(array $item): string
    { 
        $resourcePathMobile = $item['resource_path_mobile'];
        if ($resourcePathMobile) {
            /** @var \Magento\Store\Model\Store $store */
            $store = $this->storeManager->getStore();
            $imageUrlMobile = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $resourcePathMobile;
            return sprintf('<img style="width: 200px; height: auto;" src="%s" alt="%s" />', $imageUrlMobile, $resourcePathMobile);
        } else {
            return __('No image found');
        }
    }
}
