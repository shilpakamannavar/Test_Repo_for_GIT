<?php

namespace Auraine\BannerSlider\Model\Resolver\DataProvider\Banner;


use Auraine\BannerSlider\Api\Data\BannerInterface;

interface ResourcePathDataProviderInterface
{
    /**
     * @param BannerInterface $banner
     * @return string
     */
    public function resolve(BannerInterface $banner);
}