<?php

namespace Auraine\BannerSlider\Ui\Component\Listing\Column\Banner\ResourcePath;

class ExternalImage implements ProcessorInterface
{

    /**
     * External Image Resource Path
     *
     * @param array $item
     * @return string
     */
    public function process(array $item): string
    {
        $resourcePath = $item['resource_path'];
        return sprintf('<img style="width: 200px; height: auto;" src="%s" alt="%s" />', $resourcePath, $resourcePath);
    }
}
