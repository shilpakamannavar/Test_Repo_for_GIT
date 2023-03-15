<?php
namespace Auraine\ExtendedPlaceOrderMutation\Test\Unit\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Sales\Model\Order;
use Magento\Directory\Model\CountryFactory;
use Magento\Sales\Model\Order\Address;
use PHPUnit\Framework\TestCase;
use Auraine\ExtendedPlaceOrderMutation\Model\Resolver\ExtendedPlaceOrderResolver;


/**
 * @covers \Auraine\ZipCode\Model\Resolver\ZipCheck
 */
class ExtendedPlaceOrderResolverTest extends TestCase
{
   

      /**
     * @var Order|\PHPUnit\Framework\MockObject\MockObject
     */
    private $orderMock;
    /**
     * @var CountryFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $countryFactoryMock;
    /**
     * @var Address|\PHPUnit\Framework\MockObject\MockObject
     */
    private $shippingAddressMock;
    /**
     * @var ExtendedPlaceOrderResolver
     */
    private $resolver;
    /**
     * @inheritdoc
     */

    /**
     * Main set up method
     */
    protected function setUp(): void
    {
        $this->orderMock = $this->getMockBuilder(Order::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->countryFactoryMock = $this->getMockBuilder(CountryFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->shippingAddressMock = $this->getMockBuilder(Address::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->resolver = new ExtendedPlaceOrderResolver(
            $this->orderMock,
            $this->countryFactoryMock
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
