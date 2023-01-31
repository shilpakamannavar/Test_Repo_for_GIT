<?php

namespace Auraine\BannerSlider\Model\Resolver\DataProvider\Banner\ResourcePath;

use Auraine\BannerSlider\Api\Data\BannerInterface;
use Auraine\BannerSlider\Model\Resolver\DataProvider\Banner\ResourcePathDataProviderInterface;
use Magento\Widget\Model\Template\FilterEmulate;

class CustomHtml implements ResourcePathDataProviderInterface
{
    /**
     * @var FilterEmulate
     */
    private $filterEmulate;

    /**
     * CustomHtml constructor.
     *
     * @param FilterEmulate $filterEmulate
     */
    public function __construct(
        FilterEmulate $filterEmulate
    ) {
        $this->filterEmulate = $filterEmulate;
    }

    /**
     * Resolve Banner
     *
     * @param BannerInterface $banner
     * @return string
     */
    public function resolve(BannerInterface $banner)
    {
        return $this->filterEmulate->filter($banner->getResourcePath());
    }
}
