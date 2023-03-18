<?php

declare(strict_types=1);

namespace Auraine\Schedule\Api\Data;

use PHPUnit\Framework\TestCase;

// ch

class ScheduleInterfaceTest extends TestCase
{
    /**
     * @var ScheduleInterface
     */
    private $schedule;

    protected function setUp(): void
    {
        $this->schedule = new class implements ScheduleInterface {
            public function getScheduleId() {}
            public function setScheduleId($scheduleId) {}
            public function getEntityId() {}
            public function setEntityId($entityId) {}
            public function getOldBannerId() {}
            public function setOldBannerId($oldBannerId) {}
            public function getNewBannerId() {}
            public function setNewBannerId($newBannerId) {}
            public function getStartDate() {}
            public function setStartDate($startDate) {}
            public function getEndDate() {}
            public function setEndDate($endDate) {}
            public function getIsActive() {}
            public function setIsActive($isActive) {}
            public function getStatus() {}
            public function setStatus($status) {}
        };
    }

    public function testGetAndSetScheduleId()
    {
        $scheduleId = '123';
        $this->assertNull($this->schedule->getScheduleId());

        $this->schedule->setScheduleId($scheduleId);
        $this->assertSame($scheduleId, $this->schedule->getScheduleId());
    }

    public function testGetAndSetEntityId()
    {
        $entityId = '456';
        $this->assertNull($this->schedule->getEntityId());

        $this->schedule->setEntityId($entityId);
        $this->assertSame($entityId, $this->schedule->getEntityId());
    }

    public function testGetAndSetOldBannerId()
    {
        $oldBannerId = '789';
        $this->assertNull($this->schedule->getOldBannerId());

        $this->schedule->setOldBannerId($oldBannerId);
        $this->assertSame($oldBannerId, $this->schedule->getOldBannerId());
    }

    public function testGetAndSetNewBannerId()
    {
        $newBannerId = 'abc';
        $this->assertNull($this->schedule->getNewBannerId());

        $this->schedule->setNewBannerId($newBannerId);
        $this->assertSame($newBannerId, $this->schedule->getNewBannerId());
    }

    public function testGetAndSetStartDate()
    {
        $startDate = '2022-01-01';
        $this->assertNull($this->schedule->getStartDate());

        $this->schedule->setStartDate($startDate);
        $this->assertSame($startDate, $this->schedule->getStartDate());
    }

    public function testGetAndSetEndDate()
    {
        $endDate = '2022-12-31';
        $this->assertNull($this->schedule->getEndDate());

        $this->schedule->setEndDate($endDate);
        $this->assertSame($endDate, $this->schedule->getEndDate());
    }

    public function testGetAndSetIsActive()
    {
        $isActive = '1';
        $this->assertNull($this->schedule->getIsActive());

        $this->schedule->setIsActive($isActive);
        $this->assertSame($isActive, $this->schedule->getIsActive());
    }

    public function testGetAndSetStatus()
    {
        $status = 'pending';
        $this->assertNull($this->schedule->getStatus());

        $this->schedule->setStatus($status);
        $this->assertSame($status, $this->schedule->getStatus());
    }

}