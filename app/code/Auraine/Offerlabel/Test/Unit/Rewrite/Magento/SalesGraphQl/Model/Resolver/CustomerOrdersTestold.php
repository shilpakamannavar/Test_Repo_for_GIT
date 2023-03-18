<?php
declare(strict_types=1);

namespace Auraine\Offerlabel\Rewrite\Magento\SalesGraphQl\Model\Resolver;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Auraine\Offerlabel\Rewrite\Magento\SalesGraphQl\Model\Formatter\Order as OrderFormatter;
use Magento\SalesGraphQl\Model\Resolver\CustomerOrders\Query\OrderFilter;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Framework\Api\SortOrderBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Customer\Model\CustomerFactory;
use Magento\Sales\Model\OrderFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class CustomerOrdersTest extends TestCase
{
    protected $customerFactory;
    protected $orderFactory;
    /**
     * @var OrderRepositoryInterface|MockObject
     */
    private $orderRepositoryMock;

    /**
     * @var SearchCriteriaBuilder|MockObject
     */
    private $searchCriteriaBuilderMock;

    /**
     * @var OrderFilter|MockObject
     */
    private $orderFilterMock;

    /**
     * @var OrderFormatter|MockObject
     */
    private $orderFormatterMock;

    /**
     * @var SortOrderBuilder|MockObject
     */
    private $sortOrderBuilderMock;

    /**
     * @var CustomerOrders
     */
    private $customerOrdersResolver;
    private $customerModel;

    protected function setUp(): void
    {
        $this->orderRepositoryMock = $this->createMock(OrderRepositoryInterface::class);
        $this->searchCriteriaBuilderMock = $this->createMock(SearchCriteriaBuilder::class);
        $this->orderFilterMock = $this->createMock(OrderFilter::class);
        $this->orderFormatterMock = $this->createMock(OrderFormatter::class);
        $this->sortOrderBuilderMock = $this->createMock(SortOrderBuilder::class);

        $this->customerOrdersResolver = new CustomerOrders(
            $this->orderRepositoryMock,
            $this->searchCriteriaBuilderMock,
            $this->orderFilterMock,
            $this->orderFormatterMock,
            $this->sortOrderBuilderMock
        );
    
    }

   
    public function testResolve()
    {
        // Mock objects
        $field = $this->createMock(Field::class);
        $context= $this->getMockBuilder(ContextInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $info = $this->createMock(ResolveInfo::class);
        $searchResult = $this->createMock(OrderSearchResultInterface::class);
        $store = $this->createMock(StoreInterface::class);
        $extensionAttributes = $this->createMock(ExtensionAttributesInterface::class);
        $context->method('getUserId')->willReturn(1);
        $context->method('getExtensionAttributes')->willReturn($extensionAttributes);
        $extensionAttributes->method('getStore')->willReturn($store);
        $extensionAttributes->method('getIsCustomer')->willReturn(true);
        $this->orderFilter->method('createFilterGroups')->willReturn([]);
        $this->searchCriteriaBuilder->method('setFilterGroups')->willReturnSelf();
        $this->sortOrderBuilder->method('setField')->willReturnSelf();
        $this->sortOrderBuilder->method('setDirection')->willReturnSelf();
        $this->searchCriteriaBuilder->method('setSortOrders')->willReturnSelf();
        $this->orderRepository->method('getList')->withAnyParameters()->willReturn($searchResult);
        $searchResult->method('getTotalCount')->willReturn(10);
        $searchResult->method('getPageSize')->willReturn(5);
        $searchResult->method('getCurPage')->willReturn(1);
        $searchResult->method('getItems')->willReturn([]);
    
        // Test with valid arguments
        $args = ['currentPage' => 1, 'pageSize' => 5];
        $result = $this->model->resolve($field, $context, $info, null, $args);
        $this->assertArrayHasKey('total_count', $result);
        $this->assertArrayHasKey('items', $result);
        $this->assertArrayHasKey('page_info', $result);
        $this->assertEquals(10, $result['total_count']);
        $this->assertEquals([], $result['items']);
        $this->assertEquals(1, $result['page_info']['current_page']);
        $this->assertEquals(2, $result['page_info']['total_pages']);
    
        // Test with invalid currentPage argument
        $args = ['currentPage' => 0, 'pageSize' => 5];
        $this->expectException(GraphQlInputException::class);
        $this->model->resolve($field, $context, $info, null, $args);
    
        // Test with invalid pageSize argument
        $args = ['currentPage' => 1, 'pageSize' => 0];
        $this->expectException(GraphQlInputException::class);
        $this->model->resolve($field, $context, $info, null, $args);
    
        // Test with unauthorized customer
        $extensionAttributes->method('getIsCustomer')->willReturn(false);
        $args = ['currentPage' => 1, 'pageSize' => 5];
        $this->expectException(GraphQlAuthorizationException::class);
        $this->model->resolve($field, $context, $info, null, $args);
    
        // Test with InputException thrown by order repository
        $this->orderRepository->method('getList')->willThrowException(new InputException());
        $args = ['currentPage' => 1, 'pageSize' => 5];
        $this->expectException(GraphQlInputException::class);
        $this->model->resolve($field, $context, $info, null, $args);
    }
}
