<?php
namespace Auraine\CouponCodes\Test\Unit\Model\DataProvider;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\CouponCodes\Model\DataProvider\Collection
 */
class CollectionTest extends TestCase
{
    /**
     * Mock helperData
     *
     * @var \Auraine\CouponCodes\Helper\Data|PHPUnit\Framework\MockObject\MockObject
     */
    private $helperData;

    /**
     * Mock utility
     *
     * @var \Magento\SalesRule\Model\Utility|PHPUnit\Framework\MockObject\MockObject
     */
    private $utility;

    /**
     * Mock collectionFactoryInstance
     *
     * @var \Magento\SalesRule\Model\ResourceModel\Rule\Collection|PHPUnit\Framework\MockObject\MockObject
     */
    private $collectionFactoryInstance;

    /**
     * Mock collectionFactory
     *
     * @var \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $collectionFactory;

    /**
     * Mock serializer
     *
     * @var \Magento\Framework\Serialize\SerializerInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $serializer;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\CouponCodes\Model\DataProvider\Collection
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->helperData = $this->createMock(\Auraine\CouponCodes\Helper\Data::class);
        $this->utility = $this->createMock(\Magento\SalesRule\Model\Utility::class);
        $this->collectionFactoryInstance = $this->createMock(\Magento\SalesRule\Model\ResourceModel\Rule\Collection::class);
        $this->collectionFactory = $this->createMock(\Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory::class);
        $this->collectionFactory->method('create')->willReturn($this->collectionFactoryInstance);
        $this->serializer = $this->createMock(\Magento\Framework\Serialize\SerializerInterface::class);
        $this->testObject = $this->objectManager->getObject(
        \Auraine\CouponCodes\Model\DataProvider\Collection::class,
            [
                'helperData' => $this->helperData,
                'utility' => $this->utility,
                'collectionFactory' => $this->collectionFactory,
                'serializer' => $this->serializer,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetValidCouponList()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetValidCouponList
     */
    public function testGetValidCouponList(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
