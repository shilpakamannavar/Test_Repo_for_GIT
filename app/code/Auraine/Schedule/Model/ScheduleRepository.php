<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Schedule\Model;

use Auraine\Schedule\Api\Data\ScheduleInterface;
use Auraine\Schedule\Api\Data\ScheduleInterfaceFactory;
use Auraine\Schedule\Api\Data\ScheduleSearchResultsInterfaceFactory;
use Auraine\Schedule\Api\ScheduleRepositoryInterface;
use Auraine\Schedule\Model\ResourceModel\Schedule as ResourceSchedule;
use Auraine\Schedule\Model\ResourceModel\Schedule\CollectionFactory as ScheduleCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ScheduleRepository implements ScheduleRepositoryInterface
{

    /**
     * @var ScheduleInterfaceFactory
     */
    protected $scheduleFactory;

    /**
     * @var ResourceSchedule
     */
    protected $resource;

    /**
     * @var ScheduleCollectionFactory
     */
    protected $scheduleCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var Schedule
     */
    protected $searchResultsFactory;


    /**
     * @param ResourceSchedule $resource
     * @param ScheduleInterfaceFactory $scheduleFactory
     * @param ScheduleCollectionFactory $scheduleCollectionFactory
     * @param ScheduleSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceSchedule $resource,
        ScheduleInterfaceFactory $scheduleFactory,
        ScheduleCollectionFactory $scheduleCollectionFactory,
        ScheduleSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->scheduleFactory = $scheduleFactory;
        $this->scheduleCollectionFactory = $scheduleCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(ScheduleInterface $schedule)
    {
        try {
            $this->resource->save($schedule);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the schedule: %1',
                $exception->getMessage()
            ));
        }
        return $schedule;
    }

    /**
     * @inheritDoc
     */
    public function get($scheduleId)
    {
        $schedule = $this->scheduleFactory->create();
        $this->resource->load($schedule, $scheduleId);
        if (!$schedule->getId()) {
            throw new NoSuchEntityException(__('schedule with id "%1" does not exist.', $scheduleId));
        }
        return $schedule;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->scheduleCollectionFactory->create();
        
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
    public function delete(ScheduleInterface $schedule)
    {
        try {
            $scheduleModel = $this->scheduleFactory->create();
            $this->resource->load($scheduleModel, $schedule->getScheduleId());
            $this->resource->delete($scheduleModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the schedule: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($scheduleId)
    {
        return $this->delete($this->get($scheduleId));
    }
}

