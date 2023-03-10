<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Schedule\Model;

use Auraine\Schedule\Api\Data\ScheduleInterface;
use Magento\Framework\Model\AbstractModel;

class Schedule extends AbstractModel implements ScheduleInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Auraine\Schedule\Model\ResourceModel\Schedule::class);
    }

    /**
     * @inheritDoc
     */
    public function getScheduleId()
    {
        return $this->getData(self::SCHEDULE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setScheduleId($scheduleId)
    {
        return $this->setData(self::SCHEDULE_ID, $scheduleId);
    }

    /**
     * @inheritDoc
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @inheritDoc
     */
    public function getOldBannerId()
    {
        return $this->getData(self::OLD_BANNER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOldBannerId($oldBannerId)
    {
        return $this->setData(self::OLD_BANNER_ID, $oldBannerId);
    }

    /**
     * @inheritDoc
     */
    public function getNewBannerId()
    {
        return $this->getData(self::NEW_BANNER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setNewBannerId($newBannerId)
    {
        return $this->setData(self::NEW_BANNER_ID, $newBannerId);
    }

    /**
     * @inheritDoc
     */
    public function getStartDate()
    {
        return $this->getData(self::START_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setStartDate($startDate)
    {
        return $this->setData(self::START_DATE, $startDate);
    }

    /**
     * @inheritDoc
     */
    public function getEndDate()
    {
        return $this->getData(self::END_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setEndDate($endDate)
    {
        return $this->setData(self::END_DATE, $endDate);
    }

    /**
     * @inheritDoc
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
}

