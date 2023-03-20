<?php
namespace Auraine\DiscountPercentageFilter\Test\Unit\Observer;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\DiscountPercentageFilter\Observer\UpdateDiscountPercentage
 */
class UpdateDiscountPercentageTest extends TestCase
{
    /**
     * Mock dataHelper
     *
     * @var \Auraine\DiscountPercentageFilter\Helper\Data|PHPUnit\Framework\MockObject\MockObject
     */
    private $dataHelper;

    /**
     * Mock action
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Action|PHPUnit\Framework\MockObject\MockObject
     */
    private $action;

    /**
     * Mock productRepository
     *
     * @var \Magento\Catalog\Api\ProductRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $productRepository;

    /**
     * Mock storeManager
     *
     * @var \Magento\Store\Model\StoreManagerInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $storeManager;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\DiscountPercentageFilter\Observer\UpdateDiscountPercentage
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->dataHelper = $this->createMock(\Auraine\DiscountPercentageFilter\Helper\Data::class);
        $this->action = $this->createMock(\Magento\Catalog\Model\ResourceModel\Product\Action::class);
        $this->productRepository = $this->createMock(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $this->storeManager = $this->createMock(\Magento\Store\Model\StoreManagerInterface::class);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\DiscountPercentageFilter\Observer\UpdateDiscountPercentage::class,
            [
                'dataHelper' => $this->dataHelper,
                'action' => $this->action,
                'productRepository' => $this->productRepository,
                'storeManager' => $this->storeManager,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestExecute()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestExecute
     */
    public function testExecute(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetStoreIds()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetStoreIds
     */
    public function testGetStoreIds(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
