<?php

namespace Auraine\LoyaltyPoint\Test\Unit\Model\Resolver;

use Auraine\LoyaltyPoint\Model\Resolver\LoyaltyResolver;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Auraine\LoyaltyPoint\Helper\Data;

class LoyaltyResolverTest extends TestCase
{
    /**
     * @var LoyaltyResolver
     */
    protected $model;

    /**
     * @var GetCustomer|\PHPUnit\Framework\MockObject\MockObject
     */
    private $customerGetter;

    /**
     * @var Data|\PHPUnit\Framework\MockObject\MockObject
     */
    private $helperData;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->customerGetter = $this->createMock(GetCustomer::class);
        $this->helperData = $this->createMock(Data::class);

        $this->model = $this->objectManager->getObject(
            LoyaltyResolver::class,
            [
                'customerGetter' => $this->customerGetter,
                'helperData' => $this->helperData
            ]
        );
    }

    public function testResolveWithInvalidCustomerId()
    {
        $context = [];
        $customerMock = $this->createMock(\Magento\Customer\Api\Data\CustomerInterface::class);
        $customerMock->method('getId')->willReturn(null);
        $this->customerGetter->method('execute')->with($context)->willReturn($customerMock);

        $this->helperData->expects($this->never())->method('getYearOldGrandTotal');
        $this->helperData->expects($this->never())->method('getSlabValueOrName');

        $field = $this->createMock(Field::class);
        $info = $this->createMock(ResolveInfo::class);
        $value = null;
        $args = null;

        $this->expectException(\TypeError::class);
        $this->model->resolve($field, $context, $info, $value, $args);
    }

    /**
     * @dataProvider resolveDataProvider
     */
    public function testResolve($customerId, $grandTotal, $expected)
    {
        $context = $this->createMock(\Magento\GraphQl\Model\Query\ContextInterface::class);
        $customer = $this->getMockBuilder(\Magento\Customer\Api\Data\CustomerInterface::class)
            ->getMock();
        $customer->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);
        $this->customerGetter->expects($this->once())
            ->method('execute')
            ->with($context)
            ->willReturn($customer);
        $this->helperData->expects($this->once())
            ->method('getYearOldGrandTotal')
            ->with($customerId)
            ->willReturn($grandTotal);
        $this->helperData->expects($this->once())
            ->method('getSlabValueOrName')
            ->with($grandTotal, true)
            ->willReturn($expected);

        $result = $this->model->resolve(
            // new Field('testField', 'testType', [], [], [], [], [], [], [], []),
            $this->createMock(Field::class),
            $context,
            $this->createMock(ResolveInfo::class),
            null,
            null
        );

        $this->assertEquals($expected, $result);
    }

    public function resolveDataProvider()
    {
        return [
            [100, 100, 'bronze'],
            [101, 500, 'silver'],
            [102, 1000, 'gold'],
        ];
    }
}
