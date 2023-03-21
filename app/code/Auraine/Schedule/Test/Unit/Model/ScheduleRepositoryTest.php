<?php

namespace Auraine\Schedule\Test\Unit\Model;

use Auraine\Schedule\Api\Data\ScheduleInterface;
use Auraine\Schedule\Api\Data\ScheduleInterfaceFactory;
use Auraine\Schedule\Api\Data\ScheduleSearchResultsInterfaceFactory;
use Auraine\Schedule\Api\ScheduleRepositoryInterface;
use Auraine\Schedule\Model\ResourceModel\Schedule as ResourceSchedule;
use Auraine\Schedule\Model\ResourceModel\Schedule\Collection as ScheduleCollection;
use Auraine\Schedule\Model\ResourceModel\Schedule\CollectionFactory as ScheduleCollectionFactory;
use Auraine\Schedule\Model\ScheduleRepository;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\TestCase;

class ScheduleRepositoryTest extends TestCase
{
    /**
     * @var ScheduleRepository
     */
    protected $scheduleRepository;

    /**
     * @var ResourceSchedule|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $resourceMock;

    /**
     * @var ScheduleInterfaceFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $scheduleFactoryMock;

    /**
     * @var ScheduleCollectionFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $scheduleCollectionFactoryMock;

    /**
     * @var ScheduleSearchResultsInterfaceFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $searchResultsFactoryMock;

    /**
     * @var CollectionProcessorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $collectionProcessorMock;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->resourceMock = $this->getMockBuilder(ResourceSchedule::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scheduleFactoryMock = $this->getMockBuilder(ScheduleInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scheduleCollectionFactoryMock = $this->getMockBuilder(ScheduleCollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->searchResultsFactoryMock = $this->getMockBuilder(ScheduleSearchResultsInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionProcessorMock = $this->getMockBuilder(CollectionProcessorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scheduleRepository = new ScheduleRepository(
            $this->resourceMock,
            $this->scheduleFactoryMock,
            $this->scheduleCollectionFactoryMock,
            $this->searchResultsFactoryMock,
            $this->collectionProcessorMock
        );
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        $this->resourceMock = null;
        $this->scheduleFactoryMock = null;
        $this->scheduleCollectionFactoryMock = null;
        $this->searchResultsFactoryMock = null;
        $this->collectionProcessorMock = null;
        $this->scheduleRepository = null;
    }

    /**
     * @param int $scheduleId
     * @param bool $scheduleExists
     *
     * @dataProvider getScheduleDataProvider
     */
    public function testGet(int $scheduleId, bool $scheduleExists)
    {
        $scheduleMock = $this->getMockBuilder(ScheduleInterface::class)
            ->getMock();

        $this->scheduleFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($scheduleMock);

            $this->resourceMock->expects($this->once())
            ->method('load')
            ->with($scheduleMock, $scheduleId)
            ->willReturn(null);
    
        if (!$scheduleExists) {
            $this->expectException(NoSuchEntityException::class);
        }
    
        $this->scheduleRepository->get($scheduleId);
    }
    
    /**
     * @dataProvider getScheduleDataProvider
     */
    public function testDelete(int $scheduleId, bool $scheduleExists)
    {
        $scheduleMock = $this->getMockBuilder(ScheduleInterface::class)
            ->getMock();
    
        $this->scheduleFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($scheduleMock);
    
        $this->resourceMock->expects($this->once())
            ->method('load')
            ->with($scheduleMock, $scheduleId)
            ->willReturn(null);
    
        if (!$scheduleExists) {
            $this->expectException(NoSuchEntityException::class);
        }
    
        $this->resourceMock->expects($this->once())
            ->method('delete')
            ->with($scheduleMock)
            ->willThrowException(new \Exception('An error occurred while deleting the schedule.'));
    
        $this->expectException(CouldNotDeleteException::class);
    
        $this->scheduleRepository->delete($scheduleMock);
    }
    
    /**
     * @dataProvider getScheduleDataProvider
     */
    public function testSave(int $scheduleId, bool $scheduleExists)
    {
        $scheduleMock = $this->getMockBuilder(ScheduleInterface::class)
            ->getMock();
    
        $this->resourceMock->expects($this->once())
            ->method('save')
            ->with($scheduleMock)
            ->willReturn(null);
    
        $this->scheduleRepository->save($scheduleMock);
    }
    
    /**
     * @return array
     */
    public function getScheduleDataProvider()
    {
        return [
            [1, true],
            [2, false],
            [3, true],
            [4, false],
        ];
    }
}
