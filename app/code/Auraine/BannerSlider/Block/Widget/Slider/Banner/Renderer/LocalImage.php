<?php

namespace Auraine\BannerSlider\Block\Widget\Slider\Banner\Renderer;


use Auraine\BannerSlider\Api\Data\BannerInterface;
use Auraine\BannerSlider\Block\Widget\Slider\Banner\RendererInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class LocalImage extends AbstractRenderer
{

    protected $_template = 'Auraine_BannerSlider::widget/banner/renderer/local_image.phtml';

    /**
     * @return string
     */
    public function getMediaUrl()
    {
        /** @var \Magento\Store\Model\Store $store */
        try {
            $store = $this->_storeManager->getStore();
            return $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        } catch (NoSuchEntityException $e) {
            return '';
        }
    }
}