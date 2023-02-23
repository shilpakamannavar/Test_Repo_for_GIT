<?php


namespace Auraine\BannerSlider\Ui\Component\Listing\Column\Banner\ResourcePathPoster;

interface ProcessorInterfacePoster
{
    /**
     * Process method
     *
     * @param array $item
     * @return string
     */
    public function process(array $item): string;
}
