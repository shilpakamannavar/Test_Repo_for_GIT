<?php

namespace Auraine\LoyaltyPoint\Test\Unit\Model\Resolver;

use Auraine\LoyaltyPoint\Helper\Data;
use Auraine\LoyaltyPoint\Model\Resolver\CustomerLoyaltyResolver;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CustomerLoyaltyResolverTest extends TestCase
{
    /** @var Data|MockObject */
    private $helperDataMock;

    /** @var CustomerLoyaltyResolver */
    private $customerLoyaltyResolver;

    protected function setUp(): void
    {
        $this->helperDataMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->customerLoyaltyResolver = new CustomerLoyaltyResolver($this->helperDataMock);
    }

    public function testResolveThrowsExceptionWhenModelValueIsNotSet(): void
    {
        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('"model" value should be specified');

        $field = $this->createMock(Field::class);
        $context = [];
        $info = $this->createMock(ResolveInfo::class);
        $value = null;
        $args = [];

        $this->customerLoyaltyResolver->resolve($field, $context, $info, $value, $args);
    }

    public function testResolveReturnsSlabValueOrNameBasedOnGrandTotal(): void
    {
        $grandTotal = 123.45;
        $expectedResult = 'some_slab_value';

        $field = $this->createMock(Field::class);
        $context = [];
        $info = $this->createMock(ResolveInfo::class);
        $args = [];

        $value = ['model' => $this->getModelMock()];
        
        $this->helperDataMock->expects($this->once())
            ->method('getYearOldGrandTotal')
            ->with($value['model']->getId())
            ->willReturn($grandTotal);

        $this->helperDataMock->expects($this->once())
            ->method('getSlabValueOrName')
            ->with($grandTotal, true)
            ->willReturn($expectedResult);

        $result = $this->customerLoyaltyResolver->resolve($field, $context, $info, $value, $args);

        $this->assertEquals($expectedResult, $result);
    }

    private function getModelMock()
    {
        return $this->getMockBuilder(\stdClass::class)
            ->addMethods(['getId'])
            ->getMock();
    }
}