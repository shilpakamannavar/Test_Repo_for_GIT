<?php

namespace Auraine\Schedule\Cron;

use Auraine\Schedule\Api\Data\ScheduleInterface;
use Auraine\Schedule\Api\ScheduleRepositoryInterface;
use Auraine\Schedule\Cron\BannerSchedular;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Api\SortOrderInterface;
use Magento\Framework\Api\SortOrderInterfaceFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class BannerSchedularTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var BannerSchedular
     */
    protected $bannerSchedular;

    /**
     * @var ScheduleRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $scheduleRepository;

    /**
     * @var SearchCriteriaBuilder|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $searchCriteriaBuilder;

    /**
     * @var FilterBuilder|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $filterBuilder;

    /**
     * @var TimezoneInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $timezoneInterface;

    /**
     * @var ScheduleInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $schedule;

     /**
      * @var SearchResultsInterface|\PHPUnit\Framework\MockObject\MockObject
      */
    protected $searchResults;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->scheduleRepository = $this->getMockBuilder(ScheduleRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->searchCriteriaBuilder = $this->getMockBuilder(SearchCriteriaBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->filterBuilder = $this->getMockBuilder(FilterBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->timezoneInterface = $this->getMockBuilder(TimezoneInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->schedule = $this->getMockBuilder(ScheduleInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->searchResults = $this->getMockBuilder(SearchResultsInterface::class)
        ->disableOriginalConstructor()
        ->getMock();

        $this->bannerSchedular = $this->objectManager->create(
            BannerSchedular::class,
            [
                'scheduleRepository' => $this->scheduleRepository,
                'searchCriteriaBuilder' => $this->searchCriteriaBuilder,
                'filterBuilder' => $this->filterBuilder,
                'timezoneInterface' => $this->timezoneInterface,
                'searchResults' => $this->searchResults,
            ]
        );
    }

   



    public function testExecuteWhenSchedulesFound()
    {
        $schedule1 = $this->getMockBuilder(ScheduleInterface::class)
        ->disableOriginalConstructor()
        ->getMock();
        $schedule1->expects($this->once())
        ->method('getStartDate')
        ->willReturn('2023-03-20 12:00:00');

        $schedule2 = $this->getMockBuilder(ScheduleInterface::class)
        ->disableOriginalConstructor()
        ->getMock();
        $schedule2->expects($this->once())
        ->method('getStartDate')
        ->willReturn('2023-03-21 15:00:00');

        $searchResultsMock = $this->getMockBuilder(SearchResultsInterface::class)
        ->disableOriginalConstructor()
        ->getMock();
        $searchResultsMock->expects($this->once())
        ->method('getTotalCount')
        ->willReturn(2);
        $searchResultsMock->expects($this->once())
        ->method('getItems')
        ->willReturn([$schedule1, $schedule2]);

        $this->scheduleRepository->expects($this->once())
        ->method('getList')
        ->willReturn($searchResultsMock);

        $this->timezoneInterface->expects($this->exactly(2))
        ->method('date')
        ->willReturnCallback(function ($date) {
            return new \DateTime($date, new \DateTimeZone('UTC'));
        });

        $this->bannerSchedular->execute();
    }
}
