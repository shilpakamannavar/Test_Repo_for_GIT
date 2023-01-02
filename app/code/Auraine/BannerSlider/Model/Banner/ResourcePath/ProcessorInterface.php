<?php

declare(strict_types=1);

namespace Auraine\BannerSlider\Model\Banner\ResourcePath;


use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;

interface ProcessorInterface
{
    /**
     * @param RequestInterface $request
     * @return string
     * @throws LocalizedException
     */
    public function process(RequestInterface $request): string;
}