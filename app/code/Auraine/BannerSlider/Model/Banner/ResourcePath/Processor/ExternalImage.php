<?php

namespace Auraine\BannerSlider\Model\Banner\ResourcePath\Processor;

use Auraine\BannerSlider\Model\Banner\ResourcePath\ProcessorInterface;
use Magento\Framework\App\RequestInterface;

class ExternalImage implements ProcessorInterface
{

    /**
     * Process Request
     *
     * @param RequestInterface $request
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function process(RequestInterface $request): string
    {
        return $request->getParam('resource_path_external_image');
    }
}
