<?php

namespace Auraine\BannerSlider\Block\Widget\Slider\Banner;

use Auraine\BannerSlider\Api\Data\BannerInterface;

class RendererPool
{
    /**
     * @var RendererInterface[]
     */
    private $renderers;

    /**
     * RendererPool constructor.
     * @param array $renderers
     */
    public function __construct(
        array $renderers = []
    ) {
        $this->renderers = $renderers;
    }

    /**
     * Return Renderer
     *
     * @return RendererInterface[]
     */
    public function getRenderers(): array
    {
        return $this->renderers;
    }

    /**
     * Checking Banner Interface
     *
     * @param BannerInterface $banner
     * @return RendererInterface|null
     */
    public function getRenderer(BannerInterface $banner): ?RendererInterface
    {
        $renderers = $this->getRenderers();
        foreach ($renderers as $resourceType => $renderer) {
            if ($banner->getResourceType() === $resourceType) {
                return $renderer;
            }
        }
        return null;
    }
}
