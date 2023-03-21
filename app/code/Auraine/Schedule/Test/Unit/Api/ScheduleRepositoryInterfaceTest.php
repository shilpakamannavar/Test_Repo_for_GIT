<?php
declare(strict_types=1);

namespace Auraine\Schedule\Api;

use Auraine\Schedule\Api\Data\ScheduleInterfaceFactory;
use Auraine\Schedule\Api\Data\ScheduleSearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class ScheduleRepositoryInterfaceTest extends TestCase
{
    /**
     * @var ScheduleRepositoryInterface
     */
    private $scheduleRepository;

    /**
     * @var ScheduleInterfaceFactory
     */
    private $scheduleFactory;

    /**
     * @var ScheduleSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;

    /**
     * @var SearchResultsInterfaceFactory
     */
    private $searchResultsInterfaceFactory;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $objectManager = Bootstrap::getObjectManager();

        $this->scheduleRepository = $objectManager->get(ScheduleRepositoryInterface::class);
        $this->scheduleFactory = $objectManager->get(ScheduleInterfaceFactory::class);
        $this->searchResultsFactory = $objectManager->get(ScheduleSearchResultsInterfaceFactory::class);
        $this->searchCriteriaBuilderFactory = $objectManager->get(SearchCriteriaBuilderFactory::class);
        $this->searchResultsInterfaceFactory = $objectManager->get(SearchResultsInterfaceFactory::class);
    }

    /**
     * @covers \Auraine\Schedule\Api\ScheduleRepositoryInterface::save
     * @covers \Auraine\Schedule\Api\ScheduleRepositoryInterface::get
     * @covers \Auraine\Schedule\Api\ScheduleRepositoryInterface::delete
     * @covers \Auraine\Schedule\Api\ScheduleRepositoryInterface::deleteById
     */
    public function testScheduleCrud(): void
    {
        $schedule = $this->scheduleFactory->create();
        $schedule->setData([
            'schedule_id' => '123',
            'name' => 'Test Schedule',
            // add other required data for ScheduleInterface
        ]);

        // save
        $savedSchedule = $this->scheduleRepository->save($schedule);
        $this->assertInstanceOf(ScheduleInterface::class, $savedSchedule);

        // get
        $loadedSchedule = $this->scheduleRepository->get('123');
        $this->assertInstanceOf(ScheduleInterface::class, $loadedSchedule);
        $this->assertEquals('Test Schedule', $loadedSchedule->getName());

        // update
        $loadedSchedule->setName('Updated Schedule');
        $savedSchedule = $this->scheduleRepository->save($loadedSchedule);
        $this->assertInstanceOf(ScheduleInterface::class, $savedSchedule);
        $this->assertEquals('Updated Schedule', $savedSchedule->getName());

        // delete by object
        $this->assertTrue($this->scheduleRepository->delete($savedSchedule));
        $this->expectException(NoSuchEntityException::class);
        $this->scheduleRepository->get('123');

        // delete by ID
        $this->expectException(NoSuchEntityException::class);
        $this->assertTrue($this->scheduleRepository->deleteById('123'));
        $this->scheduleRepository->get('123');
    }

    /**
     * @covers \Auraine\Schedule\Api\ScheduleRepositoryInterface::getList
     */
    public function testScheduleSearch(): void
    {
    // create and save schedules
        $schedule1 = $this->scheduleFactory->create();
        $schedule1->setData([
        'schedule_id' => '123',
        'name' => 'Test Schedule 1',
    // add other required data for ScheduleInterface
        ]);
        $this->scheduleRepository->save($schedule1);


        $schedule2 = $this->scheduleFactory->create();
        $schedule2->setData([
        'schedule_id' => '456',
        'name' => 'Test Schedule 2',
        // add other required data for ScheduleInterface
        ]);
        $this->scheduleRepository->save($schedule2);

    // search by name
        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        $searchCriteria = $searchCriteriaBuilder
        ->addFilter('name', 'Test Schedule 1')
        ->create();

        $searchResults = $this->scheduleRepository->getList($searchCriteria);
        $this->assertInstanceOf(SearchResultsInterface::class, $searchResults);
        $this->assertEquals(1, $searchResults->getTotalCount());

        $items = $searchResults->getItems();
        $this->assertCount(1, $items);
        $this->assertInstanceOf(ScheduleInterface::class, $items[0]);
        $this->assertEquals('Test Schedule 1', $items[0]->getName());

    // search all
        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        $searchCriteria = $searchCriteriaBuilder->create();

        $searchResults = $this->scheduleRepository->getList($searchCriteria);
        $this->assertInstanceOf(SearchResultsInterface::class, $searchResults);
        $this->assertEquals(2, $searchResults->getTotalCount());

        $items = $searchResults->getItems();
        $this->assertCount(2, $items);
        $this->assertInstanceOf(ScheduleInterface::class, $items[0]);
        $this->assertInstanceOf(ScheduleInterface::class, $items[1]);
    }
}
