<?php


namespace Auraine\BannerSlider\Ui\Component\Listing\Column\Banner\ResourcePath;


interface ProcessorInterface
{
    /**
     * @param array $item
     * @return string
     */
    public function process(array $item): string;
}