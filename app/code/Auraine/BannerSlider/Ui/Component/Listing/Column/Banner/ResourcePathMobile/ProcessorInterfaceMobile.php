<?php


namespace Auraine\BannerSlider\Ui\Component\Listing\Column\Banner\ResourcePathMobile;

interface ProcessorInterfaceMobile
{
    /**
     * Process method
     *
     * @param array $item
     * @return string
     */
    public function process(array $item): string;
}
