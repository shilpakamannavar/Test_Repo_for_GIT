<?php

namespace Auraine\BannerSlider\Api\Data;


interface SliderSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface[]
     */
    public function getItems();

    /**
     * @param \Auraine\BannerSlider\Api\Data\SliderInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}