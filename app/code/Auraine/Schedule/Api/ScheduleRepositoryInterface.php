<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Schedule\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ScheduleRepositoryInterface
{

    /**
     * Save schedule
     * @param \Auraine\Schedule\Api\Data\ScheduleInterface $schedule
     * @return \Auraine\Schedule\Api\Data\ScheduleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Auraine\Schedule\Api\Data\ScheduleInterface $schedule
    );

    /**
     * Retrieve schedule
     * @param string $scheduleId
     * @return \Auraine\Schedule\Api\Data\ScheduleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($scheduleId);

    /**
     * Retrieve schedule matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Auraine\Schedule\Api\Data\ScheduleSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete schedule
     * @param \Auraine\Schedule\Api\Data\ScheduleInterface $schedule
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Auraine\Schedule\Api\Data\ScheduleInterface $schedule
    );

    /**
     * Delete schedule by ID
     * @param string $scheduleId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($scheduleId);
}

