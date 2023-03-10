<?php

namespace Auraine\BannerSlider\Api\Data;

interface BannerSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get Item
     *
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface[]
     */
    public function getItems();

    /**
     * Set Item
     *
     * @param \Auraine\BannerSlider\Api\Data\BannerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
