<?php

namespace Auraine\Schedule\Test\Unit\Model\Config\Source;

use Auraine\Schedule\Api\Data\ScheduleInterface;
use Auraine\Schedule\Api\ScheduleRepositoryInterface;
use Auraine\Schedule\Model\Config\Source\Schedule;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class ScheduleTest extends TestCase
{
    /**
     * @var ScheduleRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $scheduleRepository;

    /**
     * @var SearchCriteriaBuilderFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $searchCriteriaBuilderFactory;

    /**
     * @var SearchCriteriaBuilder|\PHPUnit\Framework\MockObject\MockObject
     */
    private $searchCriteriaBuilder;

    /**
     * @var Schedule
     */
    private $schedule;

    /**
     * Set up test case
     */
    protected function setUp(): void
    {
        $this->scheduleRepository = $this->getMockBuilder(ScheduleRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->searchCriteriaBuilderFactory = $this->getMockBuilder(SearchCriteriaBuilderFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->searchCriteriaBuilder = $this->getMockBuilder(SearchCriteriaBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new ObjectManager($this);

        $this->schedule = $objectManager->getObject(
            Schedule::class,
            [
                'scheduleRepository' => $this->scheduleRepository,
                'searchCriteriaBuilderFactory' => $this->searchCriteriaBuilderFactory,
            ]
        );
    }

    /**
     * Test toOptionArray method
     */
    public function testToOptionArray()
    {
        $schedule1 = $this->getMockBuilder(ScheduleInterface::class)
            ->getMockForAbstractClass();
        $schedule1->expects($this->once())
            ->method('getTitle')
            ->willReturn('Schedule 1');
        $schedule1->expects($this->once())
            ->method('getEntityId')
            ->willReturn(1);

        $schedule2 = $this->getMockBuilder(ScheduleInterface::class)
            ->getMockForAbstractClass();
        $schedule2->expects($this->once())
            ->method('getTitle')
            ->willReturn('Schedule 2');
        $schedule2->expects($this->once())
            ->method('getEntityId')
            ->willReturn(2);

        $searchCriteria = new SearchCriteria();
        $scheduleList = [$schedule1, $schedule2];

        $this->searchCriteriaBuilder->expects($this->once())
            ->method('create')
            ->willReturn($searchCriteria);

        $this->scheduleRepository->expects($this->once())
            ->method('getList')
            ->with($searchCriteria)
            ->willReturnSelf();
        $this->scheduleRepository->expects($this->once())
            ->method('getItems')
            ->willReturn($scheduleList);

        $expectedOptionArray = [
            ['value' => '', 'label' => 'Select banner'],
            ['value' => 1, 'label' => 'Schedule 1'],
            ['value' => 2, 'label' => 'Schedule 2'],
        ];
        $this->assertEquals($expectedOptionArray, $this->schedule->toOptionArray());
    }

    /**
     * Test getSearchCriteriaBuilder method
     */
    public function testGetSearchCriteriaBuilder()
    {
        $this->searchCriteriaBuilderFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->searchCriteriaBuilder);
            $this->assertEquals($this->searchCriteriaBuilder, $this->schedule->getSearchCriteriaBuilder());
    }
}
