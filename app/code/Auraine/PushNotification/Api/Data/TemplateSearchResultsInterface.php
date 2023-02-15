<?php

declare(strict_types=1);

namespace Auraine\PushNotification\Api\Data;

interface TemplateSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Template list.
     * @return \Auraine\PushNotification\Api\Data\TemplateInterface[]
     */
    public function getItems();

    /**
     * Set entity_id list.
     * @param \Auraine\PushNotification\Api\Data\TemplateInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

