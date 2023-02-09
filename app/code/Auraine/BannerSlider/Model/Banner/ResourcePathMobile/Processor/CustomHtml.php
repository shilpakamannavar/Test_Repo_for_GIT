<?php

namespace Auraine\BannerSlider\Model\Banner\ResourcePathMobile\Processor;

use Auraine\BannerSlider\Model\Banner\ResourcePathMobile\ProcessorInterfaceMobile;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;

class CustomHtml implements ProcessorInterfaceMobile
{

    /**
     * Process Request
     *
     * @param RequestInterface $request
     * @return string
     * @throws LocalizedException
     */
    public function process(RequestInterface $request): string
    {
        return $request->getParam('resource_path_mobile_custom_html');
    }
}
