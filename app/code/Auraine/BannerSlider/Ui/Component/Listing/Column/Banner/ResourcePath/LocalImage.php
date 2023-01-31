<?php

namespace Auraine\BannerSlider\Ui\Component\Listing\Column\Banner\ResourcePath;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class LocalImage implements ProcessorInterface
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
        $resourcePath = $item['resource_path'];
        if ($resourcePath) {
            /** @var \Magento\Store\Model\Store $store */
            $store = $this->storeManager->getStore();
            $imageUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $resourcePath;
            return sprintf('<img style="width: 200px; height: auto;" src="%s" alt="%s" />', $imageUrl, $resourcePath);
        } else {
            return __('No image found');
        }
    }
}
