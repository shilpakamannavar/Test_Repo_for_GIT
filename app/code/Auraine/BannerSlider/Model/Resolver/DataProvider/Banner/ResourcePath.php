<?php

namespace Auraine\BannerSlider\Model\Resolver\DataProvider\Banner;

use Auraine\BannerSlider\Api\Data\BannerInterface;

class ResourcePath implements ResourcePathDataProviderInterface
{
    /**
     * @var ResourcePathDataProviderInterface[]
     */
    private $resolvers;

    /**
     * ResourcePath constructor.
     *
     * @param array $resolvers
     */
    public function __construct(
        array $resolvers = []
    ) {
        $this->resolvers = $resolvers;
    }

    /**
     * Resolve Banner
     *
     * @param BannerInterface $banner
     * @return string
     */
    public function resolve(BannerInterface $banner)
    {
        if (isset($this->resolvers[$banner->getResourceType()])) {
            return $this->resolvers[$banner->getResourceType()]->resolve($banner);
        } else {
            return $banner->getResourcePath();
        }
    }
}
