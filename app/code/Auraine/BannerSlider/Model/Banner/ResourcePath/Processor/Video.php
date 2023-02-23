<?php

namespace Auraine\BannerSlider\Model\Banner\ResourcePath\Processor;

use Auraine\BannerSlider\Model\Banner\ResourcePath\ProcessorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;

class Video implements ProcessorInterface
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
        return $request->getParam('resource_path_video');
    }
}
