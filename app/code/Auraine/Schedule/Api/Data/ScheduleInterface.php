<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Schedule\Api\Data;

interface ScheduleInterface
{

    const STATUS = 'status';
    const NEW_BANNER_ID = 'new_banner_id';
    const SCHEDULE_ID = 'schedule_id';
    const OLD_BANNER_ID = 'old_banner_id';
    const ENTITY_ID = 'entity_id';
    const START_DATE = 'start_date';
    const END_DATE = 'end_date';
    const IS_ACTIVE = 'is_active';

    /**
     * Get schedule_id
     * @return string|null
     */
    public function getScheduleId();

    /**
     * Set schedule_id
     * @param string $scheduleId
     * @return \Auraine\Schedule\Schedule\Api\Data\ScheduleInterface
     */
    public function setScheduleId($scheduleId);

    /**
     * Get entity_id
     * @return string|null
     */
    public function getEntityId();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Auraine\Schedule\Schedule\Api\Data\ScheduleInterface
     */
    public function setEntityId($entityId);

    /**
     * Get old_banner_id
     * @return string|null
     */
    public function getOldBannerId();

    /**
     * Set old_banner_id
     * @param string $oldBannerId
     * @return \Auraine\Schedule\Schedule\Api\Data\ScheduleInterface
     */
    public function setOldBannerId($oldBannerId);

    /**
     * Get new_banner_id
     * @return string|null
     */
    public function getNewBannerId();

    /**
     * Set new_banner_id
     * @param string $newBannerId
     * @return \Auraine\Schedule\Schedule\Api\Data\ScheduleInterface
     */
    public function setNewBannerId($newBannerId);

    /**
     * Get start_date
     * @return string|null
     */
    public function getStartDate();

    /**
     * Set start_date
     * @param string $startDate
     * @return \Auraine\Schedule\Schedule\Api\Data\ScheduleInterface
     */
    public function setStartDate($startDate);

    /**
     * Get end_date
     * @return string|null
     */
    public function getEndDate();

    /**
     * Set end_date
     * @param string $endDate
     * @return \Auraine\Schedule\Schedule\Api\Data\ScheduleInterface
     */
    public function setEndDate($endDate);

    /**
     * Get is_active
     * @return string|null
     */
    public function getIsActive();

    /**
     * Set is_active
     * @param string $isActive
     * @return \Auraine\Schedule\Schedule\Api\Data\ScheduleInterface
     */
    public function setIsActive($isActive);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \Auraine\Schedule\Schedule\Api\Data\ScheduleInterface
     */
    public function setStatus($status);
}

