<?php
declare(strict_types=1);

namespace Auraine\Schedule\Test\Unit\Model;

use Auraine\Schedule\Model\Schedule;
use Auraine\Schedule\Model\ResourceModel\Schedule as ScheduleResourceModel;
use Auraine\Schedule\Api\Data\ScheduleInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class ScheduleTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
    }

    public function testGetScheduleId()
    {
        $scheduleData = [
            ScheduleInterface::SCHEDULE_ID => 1
        ];

        $schedule = $this->objectManager->getObject(Schedule::class, [
            'data' => $scheduleData,
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $this->assertEquals(1, $schedule->getScheduleId());
    }

    public function testSetScheduleId()
    {
        $schedule = $this->objectManager->getObject(Schedule::class, [
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $schedule->setScheduleId(1);

        $this->assertEquals(1, $schedule->getScheduleId());
    }

    public function testGetEntityId()
    {
        $scheduleData = [
            ScheduleInterface::ENTITY_ID => 2
        ];

        $schedule = $this->objectManager->getObject(Schedule::class, [
            'data' => $scheduleData,
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $this->assertEquals(2, $schedule->getEntityId());
    }

    public function testSetEntityId()
    {
        $schedule = $this->objectManager->getObject(Schedule::class, [
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $schedule->setEntityId(2);

        $this->assertEquals(2, $schedule->getEntityId());
    }

    public function testGetOldBannerId()
    {
        $scheduleData = [
            ScheduleInterface::OLD_BANNER_ID => 2
        ];

        $schedule = $this->objectManager->getObject(Schedule::class, [
            'data' => $scheduleData,
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $this->assertEquals(2, $schedule->getOldBannerId());
    }

    public function testSetOldBannerId()
    {
        $schedule = $this->objectManager->getObject(Schedule::class, [
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $schedule->setOldBannerId(2);

        $this->assertEquals(2, $schedule->getOldBannerId());
    }

    public function testGetNewBannerId()
    {
        $scheduleData = [
            ScheduleInterface::NEW_BANNER_ID => 2
        ];

        $schedule = $this->objectManager->getObject(Schedule::class, [
            'data' => $scheduleData,
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $this->assertEquals(2, $schedule->getNewBannerId());
    }

    public function testSetNewBannerId()
    {
        $schedule = $this->objectManager->getObject(Schedule::class, [
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $schedule->setNewBannerId(2);

        $this->assertEquals(2, $schedule->getNewBannerId());
    }


    public function testGetStartDate()
    {
        $startDate = '2022-01-01';
        $scheduleData = [
            ScheduleInterface::START_DATE => $startDate
        ];

        $schedule = $this->objectManager->getObject(Schedule::class, [
            'data' => $scheduleData,
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $this->assertEquals($startDate, $schedule->getStartDate());
    }

    public function testSetStartDate()
    {
        $startDate = '2022-01-02';
        $schedule = $this->objectManager->getObject(Schedule::class, [
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $schedule->setStartDate($startDate);

        $this->assertEquals($startDate, $schedule->getStartDate());
    }

    public function testGetEndDate()
    {
        $endDate = '2022-12-31';
        $scheduleData = [
            ScheduleInterface::END_DATE => $endDate
        ];

        $schedule = $this->objectManager->getObject(Schedule::class, [
            'data' => $scheduleData,
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $this->assertEquals($endDate, $schedule->getEndDate());
    }

    public function testSetEndDate()
    {
        $endDate = '2022-12-30';
        $schedule = $this->objectManager->getObject(Schedule::class, [
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $schedule->setEndDate($endDate);

        $this->assertEquals($endDate, $schedule->getEndDate());
    }

    public function testGetIsActive()
    {
        $scheduleData = [
            ScheduleInterface::IS_ACTIVE => 1
        ];

        $schedule = $this->objectManager->getObject(Schedule::class, [
            'data' => $scheduleData,
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $this->assertEquals(1, $schedule->getIsActive());
    }

    public function testSetIsActive()
    {
        $schedule = $this->objectManager->getObject(Schedule::class, [
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $schedule->setIsActive(1);

        $this->assertEquals(1, $schedule->getIsActive());
    }

    public function testGetStatus()
    {
        $scheduleData = [
            ScheduleInterface::STATUS => 1
        ];

        $schedule = $this->objectManager->getObject(Schedule::class, [
            'data' => $scheduleData,
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $this->assertEquals(1, $schedule->getStatus());
    }

    public function testSetStatus()
    {
        $schedule = $this->objectManager->getObject(Schedule::class, [
            'resource' => $this->createMock(ScheduleResourceModel::class),
        ]);

        $schedule->setStatus(1);

        $this->assertEquals(1, $schedule->getStatus());
    }
}
