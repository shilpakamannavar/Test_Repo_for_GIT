<?php

declare(strict_types=1);

namespace Auraine\BannerSlider\Model\Banner\ResourcePathPoster;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;

interface ProcessorInterfacePoster
{
    /**
     * Process Request
     *
     * @param RequestInterface $request
     * @return string
     * @throws LocalizedException
     */
    public function process(RequestInterface $request): string;
}
