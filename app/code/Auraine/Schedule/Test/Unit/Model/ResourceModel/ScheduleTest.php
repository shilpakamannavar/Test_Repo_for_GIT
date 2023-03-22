<?php
declare(strict_types=1);

namespace Auraine\Schedule\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\Context;
use PHPUnit\Framework\TestCase;

class ScheduleTest extends TestCase
{
    /** @var Schedule */
    private $scheduleResource;

    /** @var AdapterInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $connectionMock;

    protected function setUp(): void
    {
        $contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->connectionMock = $this->getMockBuilder(AdapterInterface::class)
            ->getMock();

        $contextMock->expects($this->once())
            ->method('getResources')
            ->willReturn($this->connectionMock);

        $this->scheduleResource = new Schedule(
            $contextMock
        );
    }

    public function testGetMainTable(): void
    {
        $this->assertSame('auraine_schedule_schedule', $this->scheduleResource->getMainTable());
    }

    public function testGetIdFieldName(): void
    {
        $this->assertSame('schedule_id', $this->scheduleResource->getIdFieldName());
    }

    public function testConstruct(): void
    {
        $this->assertSame($this->connectionMock, $this->scheduleResource->getConnection());
        $this->assertSame('auraine_schedule_schedule', $this->scheduleResource->getMainTable());
        $this->assertSame('schedule_id', $this->scheduleResource->getIdFieldName());
    }
}
