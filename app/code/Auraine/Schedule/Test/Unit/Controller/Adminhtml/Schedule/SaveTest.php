<?php

namespace Auraine\Schedule\Test\Unit\Controller\Adminhtml\Schedule;

use Auraine\Schedule\Api\Data\ScheduleInterface;
use Auraine\Schedule\Api\ScheduleRepositoryInterface;
use Auraine\Schedule\Controller\Adminhtml\Schedule\Save;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use PHPUnit\Framework\TestCase;

class SaveTest extends TestCase
{
    private $objectManager;
    private $contextMock;
    private $dataPersistorMock;
    private $scheduleRepositoryMock;
    private $searchCriteriaBuilderMock;
    private $filterBuilderMock;
    private $filterGroupBuilderMock;
    private $resultRedirectMock;
    private $scheduleMock;
    private $messageManagerMock;
    private $timezoneMock;

    private $saveController;

    public function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataPersistorMock = $this->getMockBuilder(DataPersistorInterface::class)
            ->getMock();

        $this->scheduleRepositoryMock = $this->getMockBuilder(ScheduleRepositoryInterface::class)
            ->getMock();

        $this->searchCriteriaBuilderMock = $this->getMockBuilder(SearchCriteriaBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->filterBuilderMock = $this->getMockBuilder(FilterBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->filterGroupBuilderMock = $this->getMockBuilder(FilterGroupBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultRedirectMock = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scheduleMock = $this->getMockBuilder(ScheduleInterface::class)
            ->getMock();

        $this->messageManagerMock = $this->getMockBuilder(ManagerInterface::class)
            ->getMock();

        $this->timezoneMock = $this->getMockBuilder(TimezoneInterface::class)
            ->getMock();

        $this->saveController = $this->objectManager->getObject(
            Save::class,
            [
                'context' => $this->contextMock,
                'dataPersistor' => $this->dataPersistorMock,
                'scheduleRepository' => $this->scheduleRepositoryMock,
                'searchCriteriaBuilder' => $this->searchCriteriaBuilderMock,
                'filterBuilder' => $this->filterBuilderMock,
                'filterGroupBuilder' => $this->filterGroupBuilderMock,
            ]
        );
    }

    /**
     * @dataProvider executeDataProvider
     */
    public function testExecute(\stdClass $scheduleData, $scheduleId, $shouldRedirect)
    {
        $this->scheduleMock->expects($this->once())
        ->method('getId')
        ->willReturn($scheduleId);

        $this->contextMock->expects($this->once())
        ->method('getMessageManager')
        ->willReturn($this->messageManagerMock);

        if ($scheduleId) {
            $this->scheduleRepositoryMock->expects($this->once())
                ->method('getById')
                ->with($scheduleId)
                ->willReturn($this->scheduleMock);
        } else {
            $this->scheduleRepositoryMock->expects($this->never())
                ->method('getById');
        }

        $this->dataPersistorMock->expects($this->once())
        ->method('set')
        ->with('auraine_schedule_schedule', $scheduleData);

        $this->searchCriteriaBuilderMock->expects($this->once())
        ->method('addFilter')
        ->with(ScheduleInterface::CODE, $scheduleData->getCode())
        ->willReturnSelf();

        $this->filterBuilderMock->expects($this->once())
        ->method('setField')
        ->with(ScheduleInterface::CODE)
        ->willReturnSelf();

        $this->filterBuilderMock->expects($this->once())
        ->method('setValue')
        ->with($scheduleData->getCode())
        ->willReturnSelf();

        $this->filterBuilderMock->expects($this->once())
        ->method('create')
        ->willReturnSelf();

        $this->filterGroupBuilderMock->expects($this->once())
        ->method('addFilter')
        ->willReturnSelf();

        $this->searchCriteriaBuilderMock->expects($this->once())
        ->method('setFilterGroups')
        ->willReturnSelf();

        $this->scheduleRepositoryMock->expects($this->once())
        ->method('getList')
        ->willReturn([$this->scheduleMock]);

        $this->scheduleMock->expects($this->once())
        ->method('setData')
        ->with($scheduleData->getData())
        ->willReturnSelf();

        $this->scheduleRepositoryMock->expects($this->once())
        ->method('save')
        ->with($this->scheduleMock)
        ->willReturn($this->scheduleMock);

        $this->dataPersistorMock->expects($this->once())
        ->method('clear')
        ->with('auraine_schedule_schedule');

        $this->messageManagerMock->expects($this->never())
        ->method('addErrorMessage');

        $this->resultRedirectMock->expects($this->once())
        ->method('setPath')
        ->with('*/*/index')
        ->willReturnSelf();

        $this->contextMock->expects($this->once())
        ->method('getResultRedirectFactory')
        ->willReturn($this->resultRedirectMock);

        $result = $this->saveController->execute();

        $this->assertInstanceOf(ResultInterface::class, $result);
        $this->assertInstanceOf(Redirect::class, $result);
        $this->assertEquals($shouldRedirect, $result->getShouldRedirect());
    }

    public function executeDataProvider()
    {
        $scheduleData = new \stdClass();
        $scheduleData->setCode('test_code');
        $scheduleData->setData('test_data');

        return [
        [
        $scheduleData,
        null,
        true,
        ],
        [
        $scheduleData,
        1,
        true,
        ],
        [
        $scheduleData,
        0,
        false,
        ],
        ];
    }
}
