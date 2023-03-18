<?php
declare(strict_types=1);

namespace Auraine\Offerlabel\Rewrite\Magento\SalesGraphQl\Model\Resolver;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\Exception\LocalizedException;
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
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;


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

    public function testResolveThrowsExceptionWhencustomerisnotautorized(): void
    {
        $this->assertEquals(1,1)
    }
   
}
