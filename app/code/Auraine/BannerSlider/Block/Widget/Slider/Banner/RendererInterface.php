<?php

namespace Auraine\BannerSlider\Block\Widget\Slider\Banner;

use Auraine\BannerSlider\Api\Data\BannerInterface;

interface RendererInterface
{
    /**
     * Render Function
     *
     * @param BannerInterface $banner
     * @param string $widgetClassName
     * @return string
     */
    public function render(BannerInterface $banner, string $widgetClassName): string;
}
