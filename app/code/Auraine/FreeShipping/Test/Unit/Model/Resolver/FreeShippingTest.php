<?php

namespace Auraine\FreeShipping\Test\Unit\Model\Resolver;

use Auraine\FreeShipping\Model\Resolver\FreeShipping;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Quote\Model\Quote;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;

class FreeShippingTest extends TestCase
{
    /**
     * @var FreeShipping
     */
    private $model;

    /**
     * @var StoreManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $storeManagerMock;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->storeManagerMock = $this->createMock(StoreManagerInterface::class);

        $this->model = $objectManager->getObject(FreeShipping::class, [
            'storeManager' => $this->storeManagerMock
        ]);
    }

    /**
     * @dataProvider dataProviderResolve
     */
    public function testResolve(array $value, int $subTotal, int $freeShippingAmount, array $expectedResult)
    {

        $cartMock = $this->getMockBuilder(Quote::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cartMock->method('__call')->with('getSubtotal')->willReturn($subTotal);
        $value['model'] = $cartMock;

        $storeMock = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->getMock();
        $storeMock->method('getWebsite')->willReturnSelf();
        $storeMock->method('getConfig')
                  ->with('carriers/freeshipping/free_shipping_subtotal')
                  ->willReturn($freeShippingAmount);

        $this->storeManagerMock->method('getStore')->willReturn($storeMock);

        $fieldMock = $this->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();

        $contextMock = $this->getMockBuilder(ContextInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $infoMock = $this->getMockBuilder(ResolveInfo::class)
            ->disableOriginalConstructor()
            ->getMock();

       $this->assertEquals($expectedResult, $this->model->resolve($fieldMock, $contextMock, $infoMock, $value));

    }

    public function testResolveWithMissingModelValueThrowsException()
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage('"model" value must be specified');

        $field = $this->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();

        $context = $this->getMockBuilder(ContextInterface::class)
            ->getMock();

        $info = $this->getMockBuilder(ResolveInfo::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->model->resolve($field, $context, $info, []);

    }

    public function dataProviderResolve(): array
    {
        return [
            [
                [
                    'model' => null
                ],
                0,
                0,
                [
                    "status" => true,
                    "amount" => 0
                ]
            ],
            [
                [
                    'model' => null
                ],
                50,
                100,
                [
                    "status" => false,
                    "amount" => 50
                ]
            ],
            [
                [
                    'model' => null
                ],
                100,
                100,
                [
                    "status" => true,
                    "amount" => 0
                ]
            ]
        ];
    }

}

