<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\PushNotification\Model;

use Auraine\PushNotification\Api\Data\SubscriberInterface;
use Auraine\PushNotification\Api\Data\SubscriberInterfaceFactory;
use Auraine\PushNotification\Api\Data\SubscriberSearchResultsInterfaceFactory;
use Auraine\PushNotification\Api\SubscriberRepositoryInterface;
use Auraine\PushNotification\Model\ResourceModel\Subscriber as ResourceSubscriber;
use Auraine\PushNotification\Model\ResourceModel\Subscriber\CollectionFactory as SubscriberCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class SubscriberRepository implements SubscriberRepositoryInterface
{

    /**
     * @var ResourceSubscriber
     */
    protected $resource;

    /**
     * @var SubscriberCollectionFactory
     */
    protected $subscriberCollectionFactory;

    /**
     * @var Subscriber
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var SubscriberInterfaceFactory
     */
    protected $subscriberFactory;


    /**
     * @param ResourceSubscriber $resource
     * @param SubscriberInterfaceFactory $subscriberFactory
     * @param SubscriberCollectionFactory $subscriberCollectionFactory
     * @param SubscriberSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceSubscriber $resource,
        SubscriberInterfaceFactory $subscriberFactory,
        SubscriberCollectionFactory $subscriberCollectionFactory,
        SubscriberSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->subscriberFactory = $subscriberFactory;
        $this->subscriberCollectionFactory = $subscriberCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(SubscriberInterface $subscriber)
    {
        try {
            $this->resource->save($subscriber);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the subscriber: %1',
                $exception->getMessage()
            ));
        }
        return $subscriber;
    }

    /**
     * @inheritDoc
     */
    public function get($subscriberId)
    {
        $subscriber = $this->subscriberFactory->create();
        $this->resource->load($subscriber, $subscriberId);
        if (!$subscriber->getId()) {
            throw new NoSuchEntityException(__('Subscriber with id "%1" does not exist.', $subscriberId));
        }
        return $subscriber;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->subscriberCollectionFactory->create();
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(SubscriberInterface $subscriber)
    {
        try {
            $subscriberModel = $this->subscriberFactory->create();
            $this->resource->load($subscriberModel, $subscriber->getSubscriberId());
            $this->resource->delete($subscriberModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Subscriber: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($subscriberId)
    {
        return $this->delete($this->get($subscriberId));
    }
}

