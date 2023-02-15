<?php

declare(strict_types=1);

namespace Auraine\PushNotification\Api\Data;

interface SubscriberSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Subscriber list.
     * @return \Auraine\PushNotification\Api\Data\SubscriberInterface[]
     */
    public function getItems();

    /**
     * Set customer_id list.
     * @param \Auraine\PushNotification\Api\Data\SubscriberInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

