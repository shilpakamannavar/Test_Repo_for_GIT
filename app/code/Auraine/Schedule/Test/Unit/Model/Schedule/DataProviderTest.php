<?php
declare(strict_types=1);

namespace Auraine\Schedule\Model\Schedule;

use Auraine\Schedule\Model\ResourceModel\Schedule\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class DataProviderTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var DataProvider
     */
    protected $dataProvider;

    /**
     * @var CollectionFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $collectionFactoryMock;

    /**
     * @var DataPersistorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $dataPersistorMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        $this->collectionFactoryMock = $this->createMock(CollectionFactory::class);
        $this->dataPersistorMock = $this->createMock(DataPersistorInterface::class);

        $this->dataProvider = $this->objectManager->getObject(
            DataProvider::class,
            [
                'name' => 'test_name',
                'primaryFieldName' => 'test_primary_field_name',
                'requestFieldName' => 'test_request_field_name',
                'collectionFactory' => $this->collectionFactoryMock,
                'dataPersistor' => $this->dataPersistorMock,
                'meta' => [],
                'data' => [],
            ]
        );
    }

    /**
     * Test for getData method.
     *
     * @return void
     */
    public function testGetData(): void
    {
        $modelMock = $this->getMockBuilder(\Magento\Framework\Model\AbstractModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        $modelMock->expects($this->any())
            ->method('getId')
            ->willReturn(1);

        $modelMock->expects($this->any())
            ->method('getData')
            ->willReturn(['test_data' => 'test_value']);

        $collectionMock = $this->createMock(\Auraine\Schedule\Model\ResourceModel\Schedule\Collection::class);
        $collectionMock->expects($this->any())
            ->method('getItems')
            ->willReturn([$modelMock]);

        $this->collectionFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($collectionMock);

        $this->dataPersistorMock->expects($this->any())
            ->method('get')
            ->with('auraine_schedule_schedule')
            ->willReturn(['test_data' => 'test_value']);

        $this->dataPersistorMock->expects($this->once())
            ->method('clear')
            ->with('auraine_schedule_schedule');

        $result = $this->dataProvider->getData();

        $this->assertEquals(1, count($result));
        $this->assertEquals(['test_data' => 'test_value'], reset($result));
    }
}
