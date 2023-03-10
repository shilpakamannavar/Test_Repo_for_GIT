<?php

namespace Auraine\BannerSlider\Api\Data;

interface SliderSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Slider Interface
     *
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface[]
     */
    public function getItems();

    /**
     * Slider Search Interface
     *
     * @param \Auraine\BannerSlider\Api\Data\SliderInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
