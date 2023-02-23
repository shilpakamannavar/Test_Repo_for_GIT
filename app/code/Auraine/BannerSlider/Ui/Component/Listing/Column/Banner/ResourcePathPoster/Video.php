<?php

namespace Auraine\BannerSlider\Ui\Component\Listing\Column\Banner\ResourcePathPoster;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class Video implements ProcessorInterfacePoster
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
        $resourcePathPoster= $item['resource_path_poster'];
        if ($resourcePathPoster) {
            /** @var \Magento\Store\Model\Store $store */
            $store = $this->storeManager->getStore();
            $imageUrlPoster = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $resourcePathPoster;
            return sprintf('<img style="width: 200px; height: auto;" src="%s" alt="%s" />', $imageUrlPoster, $resourcePathPoster);
        } else {
            return __('No image found');
        }
    }
}
