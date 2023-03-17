<?php

namespace Auraine\Offerlabel\Test\Unit\Rewrite\Magento\SalesGraphQl\Model\Resolver;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Auraine\Offerlabel\Rewrite\Magento\SalesGraphQl\Model\Formatter\Order as OrderFormatter;
use Auraine\Offerlabel\Rewrite\Magento\SalesGraphQl\Model\Resolver\CustomerOrders;
use Magento\SalesGraphQl\Model\Resolver\CustomerOrders\Query\OrderFilter;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Framework\Api\SortOrderBuilder;

class CustomerOrdersTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CustomerOrders
     */
    private $customerOrdersResolver;

    /**
     * @var OrderRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $orderRepository;

    /**
     * @var SearchCriteriaBuilder|\PHPUnit\Framework\MockObject\MockObject
     */
    private $searchCriteriaBuilder;

    /**
     * @var OrderFilter|\PHPUnit\Framework\MockObject\MockObject
     */
    private $orderFilter;

    /**
     * @var OrderFormatter|\PHPUnit\Framework\MockObject\MockObject
     */
    private $orderFormatter;

    /**
     * @var SortOrderBuilder|\PHPUnit\Framework\MockObject\MockObject
     */
    private $sortOrderBuilder;

    /**
     * @var ContextInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $context;

    /**
     * @var ResolveInfo|\PHPUnit\Framework\MockObject\MockObject
     */
    private $resolveInfo;

    protected function setUp(): void
    {
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);
        $this->searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);
        $this->orderFilter = $this->createMock(OrderFilter::class);
        $this->orderFormatter = $this->createMock(OrderFormatter::class);
        $this->sortOrderBuilder = $this->createMock(SortOrderBuilder::class);
        $this->context = $this->createMock(ContextInterface::class);
        $this->resolveInfo = $this->createMock(ResolveInfo::class);

        $this->customerOrdersResolver = new CustomerOrders(
            $this->orderRepository,
            $this->searchCriteriaBuilder,
            $this->orderFilter,
            $this->orderFormatter,
            $this->sortOrderBuilder
        );
    }

    public function testGetSearchResult()
    {
        $this->assertEquals(1, 1);
    }
}