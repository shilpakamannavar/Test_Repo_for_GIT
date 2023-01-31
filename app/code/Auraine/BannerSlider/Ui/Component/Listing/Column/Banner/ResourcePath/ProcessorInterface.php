<?php


namespace Auraine\BannerSlider\Ui\Component\Listing\Column\Banner\ResourcePath;

interface ProcessorInterface
{
    /**
     * Process method
     *
     * @param array $item
     * @return string
     */
    public function process(array $item): string;
}
