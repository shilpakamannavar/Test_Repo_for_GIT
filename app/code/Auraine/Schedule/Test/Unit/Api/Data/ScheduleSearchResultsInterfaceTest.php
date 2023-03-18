<?php
declare(strict_types=1);

namespace Auraine\Schedule\Api\Data;

use PHPUnit\Framework\TestCase;

class ScheduleSearchResultsInterfaceTest extends TestCase
{
    /**
     * @var ScheduleSearchResultsInterface
     */
    private $scheduleSearchResults;

    protected function setUp(): void
    {
        $this->scheduleSearchResults = new class implements ScheduleSearchResultsInterface {
            public function getItems() {}
            public function setItems(array $items) {}
            public function getSearchCriteria() {}
            public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null) {}
            public function getTotalCount() {}
            public function setTotalCount($totalCount) {}
        };
    }

    public function testGetAndSetItems()
    {
        $items = [
            $this->createMock(ScheduleInterface::class),
            $this->createMock(ScheduleInterface::class),
        ];
        $this->scheduleSearchResults->setItems($items);
        $this->assertSame($items, $this->scheduleSearchResults->getItems());
    }

    public function testGetAndSetSearchCriteria()
    {
        $searchCriteria = $this->createMock(\Magento\Framework\Api\SearchCriteriaInterface::class);
        $this->scheduleSearchResults->setSearchCriteria($searchCriteria);
        $this->assertSame($searchCriteria, $this->scheduleSearchResults->getSearchCriteria());
    }

    public function testGetAndSetTotalCount()
    {
        $totalCount = 5;
        $this->scheduleSearchResults->setTotalCount($totalCount);
        $this->assertSame($totalCount, $this->scheduleSearchResults->getTotalCount());
    }
}
