<?php

declare(strict_types=1);

namespace Auraine\PushNotification\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface SubscriberRepositoryInterface
{

    /**
     * Save Subscriber
     * @param \Auraine\PushNotification\Api\Data\SubscriberInterface $subscriber
     * @return \Auraine\PushNotification\Api\Data\SubscriberInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Auraine\PushNotification\Api\Data\SubscriberInterface $subscriber
    );

    /**
     * Retrieve Subscriber
     * @param string $subscriberId
     * @return \Auraine\PushNotification\Api\Data\SubscriberInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($subscriberId);

    /**
     * Retrieve Subscriber matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Auraine\PushNotification\Api\Data\SubscriberSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Subscriber
     * @param \Auraine\PushNotification\Api\Data\SubscriberInterface $subscriber
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Auraine\PushNotification\Api\Data\SubscriberInterface $subscriber
    );

    /**
     * Delete Subscriber by ID
     * @param string $subscriberId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($subscriberId);
}

