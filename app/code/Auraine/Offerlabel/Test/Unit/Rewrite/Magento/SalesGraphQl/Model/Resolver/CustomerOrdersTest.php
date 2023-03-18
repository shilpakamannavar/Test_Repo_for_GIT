<?php
namespace Auraine\Offerlabel\Test\Unit\Rewrite\Magento\SalesGraphQl\Model\Resolver;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\Offerlabel\Rewrite\Magento\SalesGraphQl\Model\Resolver\CustomerOrders
 */
class CustomerOrdersTest extends TestCase
{
    /**
     * Mock orderRepository
     *
     * @var \Magento\Sales\Api\OrderRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderRepository;

    /**
     * Mock searchCriteriaBuilder
     *
     * @var \Magento\Framework\Api\SearchCriteriaBuilder|PHPUnit\Framework\MockObject\MockObject
     */
    private $searchCriteriaBuilder;

    /**
     * Mock orderFilter
     *
     * @var \Magento\SalesGraphQl\Model\Resolver\CustomerOrders\Query\OrderFilter|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderFilter;

    /**
     * Mock orderFormatter
     *
     * @var \Auraine\Offerlabel\Rewrite\Magento\SalesGraphQl\Model\Formatter\Order|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderFormatter;

    /**
     * Mock sortOrderBuilder
     *
     * @var \Magento\Framework\Api\SortOrderBuilder|PHPUnit\Framework\MockObject\MockObject
     */
    private $sortOrderBuilder;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\Offerlabel\Rewrite\Magento\SalesGraphQl\Model\Resolver\CustomerOrders
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->orderRepository = $this->createMock(\Magento\Sales\Api\OrderRepositoryInterface::class);
        $this->searchCriteriaBuilder = $this->createMock(\Magento\Framework\Api\SearchCriteriaBuilder::class);
        $this->orderFilter = $this->createMock(\Magento\SalesGraphQl\Model\Resolver\CustomerOrders\Query\OrderFilter::class);
        $this->orderFormatter = $this->createMock(\Auraine\Offerlabel\Rewrite\Magento\SalesGraphQl\Model\Formatter\Order::class);
        $this->sortOrderBuilder = $this->createMock(\Magento\Framework\Api\SortOrderBuilder::class);
        $this->testObject = $this->objectManager->getObject(
        \Auraine\Offerlabel\Rewrite\Magento\SalesGraphQl\Model\Resolver\CustomerOrders::class,
            [
                'orderRepository' => $this->orderRepository,
                'searchCriteriaBuilder' => $this->searchCriteriaBuilder,
                'orderFilter' => $this->orderFilter,
                'orderFormatter' => $this->orderFormatter,
                'sortOrderBuilder' => $this->sortOrderBuilder,
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
