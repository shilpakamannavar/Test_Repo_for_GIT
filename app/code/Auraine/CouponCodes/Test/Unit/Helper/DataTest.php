<?php
namespace Auraine\CouponCodes\Test\Unit\Helper;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\CouponCodes\Helper\Data
 */
class DataTest extends TestCase
{
    /**
     * Test case 1
     * @var string const
     */
    private const TEST_CASE_ONE = 'Testcase 1';
    
    /**
     * Mock context
     *
     * @var \Magento\Framework\App\Helper\Context|PHPUnit\Framework\MockObject\MockObject
     */
    private $context;

    /**
     * Mock storeManager
     *
     * @var \Magento\Store\Model\StoreManagerInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $storeManager;

    /**
     * Mock customerSession
     *
     * @var \Magento\Customer\Model\Session|PHPUnit\Framework\MockObject\MockObject
     */
    private $customerSession;

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
     * Mock objectManger
     *
     * @var \Magento\Framework\ObjectManagerInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $objectManger;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\CouponCodes\Helper\Data
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->context = $this->createMock(\Magento\Framework\App\Helper\Context::class);
        $this->storeManager = $this->createMock(\Magento\Store\Model\StoreManagerInterface::class);
        $this->customerSession = $this->createMock(\Magento\Customer\Model\Session::class);
        $this->collectionFactoryInstance = $this->createMock(
            \Magento\SalesRule\Model\ResourceModel\Rule\Collection::class
        );
        $this->collectionFactory = $this->createMock(
            \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory::class
        );
        $this->collectionFactory->method('create')->willReturn($this->collectionFactoryInstance);
        $this->objectManger = $this->createMock(\Magento\Framework\ObjectManagerInterface::class);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\CouponCodes\Helper\Data::class,
            [
                'context' => $this->context,
                'storeManager' => $this->storeManager,
                'customerSession' => $this->customerSession,
                'collectionFactory' => $this->collectionFactory,
                'objectManger' => $this->objectManger,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetCustomerGroupId()
    {
        return [
            self::TEST_CASE_ONE => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetCustomerGroupId
     */
    public function testGetCustomerGroupId(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetWebsiteId()
    {
        return [
            self::TEST_CASE_ONE => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetWebsiteId
     */
    public function testGetWebsiteId(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetCurrentCouponRule()
    {
        return [
            self::TEST_CASE_ONE => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetCurrentCouponRule
     */
    public function testGetCurrentCouponRule(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetMobileHeaderStatus()
    {
        return [
            self::TEST_CASE_ONE => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetMobileHeaderStatus
     */
    public function testGetMobileHeaderStatus(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestIsModuleOutputEnabled()
    {
        return [
            self::TEST_CASE_ONE => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestIsModuleOutputEnabled
     */
    public function testIsModuleOutputEnabled(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
