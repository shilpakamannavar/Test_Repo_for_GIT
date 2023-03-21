<?php
declare(strict_types=1);

namespace Auraine\Schedule\Model\ResourceModel\Schedule;

use Auraine\Schedule\Model\ResourceModel\Schedule\Collection;
use Auraine\Schedule\Model\Schedule;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /** @var Collection */
    private $collection;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->collection = $objectManager->getObject(
            Collection::class,
            [
                'scheduleFactory' => $objectManager->getFactoryMock(
                    Schedule::class,
                    ['create' => $objectManager->getObject(Schedule::class)]
                )
            ]
        );
    }

    public function testGetMainTable(): void
    {
        $this->assertSame('auraine_schedule', $this->collection->getMainTable());
    }

    public function testSetIdFieldName(): void
    {
        $this->assertSame('schedule_id', $this->collection->getIdFieldName());
    }

    public function testSetModel(): void
    {
        $this->assertInstanceOf(Schedule::class, $this->collection->getNewEmptyItem());
    }
}
