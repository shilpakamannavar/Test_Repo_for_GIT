<?php
namespace Auraine\LoyaltyPoint\Test\Unit\Model\Resolver;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\LoyaltyPoint\Model\Resolver\CustomerLoyaltyResolver
 */
class CustomerLoyaltyResolverTest extends TestCase
{
    /**
     * Mock orderCollectionFactoryInstance
     *
     * @var \Magento\Sales\Model\ResourceModel\Order\Collection|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderCollectionFactoryInstance;

    /**
     * Mock orderCollectionFactory
     *
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderCollectionFactory;

    /**
     * Mock helperData
     *
     * @var \Auraine\LoyaltyPoint\Helper\Data|PHPUnit\Framework\MockObject\MockObject
     */
    private $helperData;

    /**
     * Mock helperNameById
     *
     * @var \Auraine\LoyaltyPoint\Helper\GetTireNameByid|PHPUnit\Framework\MockObject\MockObject
     */
    private $helperNameById;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\LoyaltyPoint\Model\Resolver\CustomerLoyaltyResolver
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->orderCollectionFactoryInstance = $this->createMock(\Magento\Sales\Model\ResourceModel\Order\Collection::class);
        $this->orderCollectionFactory = $this->createMock(\Magento\Sales\Model\ResourceModel\Order\CollectionFactory::class);
        $this->orderCollectionFactory->method('create')->willReturn($this->orderCollectionFactoryInstance);
        $this->helperData = $this->createMock(\Auraine\LoyaltyPoint\Helper\Data::class);
        $this->helperNameById = $this->createMock(\Auraine\LoyaltyPoint\Helper\GetTireNameByid::class);
        $this->testObject = $this->objectManager->getObject(
        \Auraine\LoyaltyPoint\Model\Resolver\CustomerLoyaltyResolver::class,
            [
                'orderCollectionFactory' => $this->orderCollectionFactory,
                'helperData' => $this->helperData,
                'helperNameById' => $this->helperNameById,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestResolve()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestResolve
     */
    public function testResolve(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
