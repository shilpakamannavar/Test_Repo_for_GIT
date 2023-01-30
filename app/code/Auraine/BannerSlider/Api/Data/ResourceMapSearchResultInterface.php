<?php

namespace Auraine\BannerSlider\Api\Data;

interface ResourceMapSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get Items
     *
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface[]
     */
    public function getItems();

    /**
     * Set Items
     *
     * @param \Auraine\BannerSlider\Api\Data\ResourceMapInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
