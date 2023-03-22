<?php

namespace Auraine\DiscountPercentageFilter\Test\Unit\Observer;

use Auraine\DiscountPercentageFilter\Observer\UpdateDiscountPercentage;
use Auraine\DiscountPercentageFilter\Helper\Data;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Catalog\Model\ResourceModel\Product\Action;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;

class UpdateDiscountPercentageTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var UpdateDiscountPercentage
     */
    private $observer;

    /**
     * @var Data|\PHPUnit\Framework\MockObject\MockObject
     */
    private $dataHelperMock;

    /**
     * @var Action|\PHPUnit\Framework\MockObject\MockObject
     */
    private $actionMock;

    /**
     * @var ProductRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $productRepositoryMock;

    /**
     * @var StoreManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $storeManagerMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        $this->dataHelperMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->actionMock = $this->getMockBuilder(Action::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->productRepositoryMock = $this->getMockBuilder(ProductRepositoryInterface::class)
            ->getMock();
        $this->storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->getMock();
        $this->observer = $this->objectManager->getObject(
            UpdateDiscountPercentage::class,
            [
                'dataHelper' => $this->dataHelperMock,
                'action' => $this->actionMock,
                'productRepository' => $this->productRepositoryMock,
                'storeManager' => $this->storeManagerMock,
            ]
        );
    }

    /**
     * Test observer instance
     */
    public function testInstanceOfObserverInterface()
    {
        $this->assertInstanceOf(ObserverInterface::class, $this->observer);
    }

    /**
     * Test execute method with a product that doesn't have a SKU
     */
    public function testExecuteWithNoSkuProduct()
    {
        $productMock = $this->getMockBuilder(\Magento\Catalog\Model\Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productMock->expects($this->once())
            ->method('getTypeId')
            ->willReturn('simple');
        
        // Set the SKU property directly on the product mock
        $productMock->sku = null;
    
        $observer = new Observer(['product' => $productMock]);
        $this->actionMock->expects($this->never())
            ->method('updateAttributes');
        $this->observer->execute($observer);
    }

    /**
     * Test execute method with a product that has a SKU
     */
    public function testExecuteWithSkuProduct()
    {
        $typeId = 'simple';
        $productMock = $this->getMockBuilder(\Magento\Catalog\Model\Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productMock->expects($this->once())
                ->method('getTypeId')
                ->willReturn('simple');
        
        // Set the SKU property directly on the product mock
        $productMock->sku = 'test_sku';
        $productMock->price = 100;
        $productMock->special_price = 80;

        $productMock->expects($this->once())->method('getTypeId')->willReturn($typeId);

        $productRepoMock = $this->createMock(\Magento\Catalog\Api\ProductRepositoryInterface::class);

        $productRepoMock->method('get')->with('test_sku')->willReturn($productMock);

        $observer = new Observer(['product' => $productMock]);
        $this->actionMock->expects($this->never())
            ->method('updateAttributes');
        $this->observer->execute($observer);
    }
}
