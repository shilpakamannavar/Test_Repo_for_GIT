<?php
namespace Auraine\LoyaltyPoint\Test\Unit\Helper;

use Auraine\LoyaltyPoint\Helper\Data;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Sales\Model\ResourceModel\Order\Collection;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Model\Order;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Data
     */
    private $dataHelper;

    /**
     * @var ScopeConfigInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $scopeConfigMock;

    /**
     * @var CollectionFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $orderCollectionFactoryMock;

    /**
     * @var Collection|\PHPUnit\Framework\MockObject\MockObject
     */
    private $orderCollectionMock;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->scopeConfigMock = $this->createMock(ScopeConfigInterface::class);
        $this->orderCollectionFactoryMock = $this->createMock(CollectionFactory::class);
        $this->orderCollectionMock = $this->createMock(Collection::class);

        $this->dataHelper = $this->objectManager->getObject(Data::class, [
            'scopeConfig' => $this->scopeConfigMock,
            'orderCollectionFactory' => $this->orderCollectionFactoryMock,
        ]);
    }

    public function testGetYearOldGrandTotalReturnsCorrectValue()
    {
        $customerId = 1;

        $this->orderCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->orderCollectionMock);

        $this->orderCollectionMock->expects($this->any())
            ->method('addFieldToFilter')
            ->withConsecutive(
                [$this->equalTo('customer_id'), $this->equalTo($customerId)],
                [$this->equalTo('state'), $this->equalTo(Order::STATE_COMPLETE)],
                [$this->equalTo('created_at'), $this->equalTo(['lteq' => date('Y-m-d H:i:s')])],
                [$this->equalTo('created_at'), $this->equalTo(['gteq' => date('Y-m-d H:i:s', strtotime('-1 year'))])]
            )
            ->willReturnSelf();

        $orderMock1 = $this->createMock(Order::class);
        $orderMock1->expects($this->once())
            ->method('getGrandTotal')
            ->willReturn(100);

        $orderMock2 = $this->createMock(Order::class);
        $orderMock2->expects($this->once())
            ->method('getGrandTotal')
            ->willReturn(200);

        $this->orderCollectionMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator([$orderMock1, $orderMock2]));

        $this->assertEquals(300, $this->dataHelper->getYearOldGrandTotal($customerId));
    }

    public function testGetSlabValueOrNameWithLowGrandTotal()
    {
        $dataHelper = new \Auraine\LoyaltyPoint\Helper\Data(
            $this->createMock(\Magento\Framework\App\Config\ScopeConfigInterface::class),
            $this->createMock(\Magento\Sales\Model\ResourceModel\Order\CollectionFactory::class)
        );
        
        $slabValue = $dataHelper->getSlabValueOrName(100);
        
        $this->assertNull($slabValue, 'Slab value should be null for a grand total less than all slabs');
    }
}
