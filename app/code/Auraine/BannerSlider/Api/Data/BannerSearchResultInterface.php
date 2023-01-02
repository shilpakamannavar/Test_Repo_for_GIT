<?php

namespace Auraine\BannerSlider\Api\Data;


interface BannerSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface[]
     */
    public function getItems();

    /**
     * @param \Auraine\BannerSlider\Api\Data\BannerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}