<?php
declare(strict_types=1);

namespace Auraine\ExtendedPlaceOrderMutation\Test\Unit\Model\Resolver;

use Auraine\ExtendedPlaceOrderMutation\Model\Resolver\ExtendedPlaceOrderResolver;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\IdentityInterface;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Address;
use Magento\Directory\Model\Country;
use Magento\Directory\Model\CountryFactory;


class ExtendedPlaceOrderResolverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ExtendedPlaceOrderResolver
     */
    private $resolver;



    /**
     * @var \Magento\Sales\Model\Order|\PHPUnit\Framework\MockObject\MockObject
     */
    private $orderMock;

    /**
     * @var \Magento\Directory\Model\CountryFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $countryFactoryMock;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->orderMock = $this->getMockBuilder(\Magento\Sales\Model\Order::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->countryFactoryMock = $this->getMockBuilder(\Magento\Directory\Model\CountryFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->shippingAddressMock = $this->getMockBuilder(\Magento\Sales\Model\Order\Address::class)
                    ->disableOriginalConstructor()
                    ->getMock();

        $this->resolver = $objectManager->getObject(
            ExtendedPlaceOrderResolver::class,
            [
                'order' => $this->orderMock,
                'shippingAddressMock' => $this->shippingAddressMock,
                'countryFactory' => $this->countryFactoryMock,
            ]
        );
    }

    /**
     * @dataProvider emptyOrderNumberDataProvider
     */
    public function testResolveWhenOrderNumberIsEmpty($orderNumber)
    {
        $fieldMock = $this->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();
        $contextMock = [];
        $infoMock = $this->getMockBuilder(ResolveInfo::class)
            ->disableOriginalConstructor()
            ->getMock();

        $result = $this->resolver->resolve(
            $fieldMock,
            $contextMock,
            $infoMock,
            ['order_number' => $orderNumber],
            null
        );

        $this->assertNull($result);
    }

    public function emptyOrderNumberDataProvider()
    {
        return [
            [''],
            [null],
        ];
    }

    public function testResolveWhenShippingAddressIsEmpty()
    {

        $orderNumber = '100000001';
        $fieldMock = $this->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();
        $contextMock = [];
        $infoMock = $this->getMockBuilder(ResolveInfo::class)
            ->disableOriginalConstructor()
            ->getMock();
        $orderMock = $this->getMockBuilder(\Magento\Sales\Model\Order::class)
            ->disableOriginalConstructor()
            ->getMock();

        $orderMock->expects($this->any())
            ->method('loadByIncrementId')
            ->with($orderNumber)
            ->willReturnSelf();

        $orderMock->expects($this->any())
            ->method('getShippingAddress')
            ->willReturn(null);

        $this->orderMock->expects($this->any())
            ->method('loadByIncrementId')
            ->with($orderNumber)
            ->willReturn($orderMock);

        $result = $this->resolver->resolve(
            $fieldMock,
            $contextMock,
            $infoMock,
            [],
            null
        );

        $this->assertNull($result);

    }



public function testResolveWhenShippingAddressIsNotEmpty()
{
    $orderNumber = '100000001';
    $firstName = 'John';
    $lastName = 'Doe';
    $email = 'john.doe@example.com';
    $telephone = '+1-123-456-7890';
    $countryId = 'IN';
    $countryName = 'India';
    $city = 'Bengaluru';
    $region = 'Bengaluru';
    $streetName = ['St. Nicholas Greek Orthodox Church' ];

    $value = ['order_number' => $orderNumber];
    $this->orderMock->expects($this->once())
        ->method('loadByIncrementId')
        ->with($orderNumber)
        ->willReturnSelf();
        $this->shippingAddressMock->expects($this->once())
        ->method('getData')
        ->willReturn([
            'firstName' => $firstName,
            'lastname' => $lastName,
            'email' => $email,
        ]);

        $this->shippingAddressMock->expects($this->once())
        ->method('getFirstName')
        ->willReturn($firstName);
        $this->shippingAddressMock->expects($this->once())
        ->method('getLastName')
        ->willReturn($lastName);
        $this->shippingAddressMock->expects($this->once())
        ->method('getTelephone')
        ->willReturn($telephone);
        $this->shippingAddressMock->expects($this->once())
        ->method('getCity')
        ->willReturn($city);
        $this->shippingAddressMock->expects($this->once())
        ->method('getRegion')
        ->willReturn($region);
        $this->shippingAddressMock->expects($this->once())
        ->method('getStreet')
        ->willReturn($streetName);
        $this->shippingAddressMock->expects($this->once())
        ->method('getCountryId')
        ->willReturn($countryId);
        $this->orderMock->expects($this->once())
        ->method('getCustomerEmail')
        ->willReturn($email);
         $this->orderMock->expects($this->once())
        ->method('getShippingAddress')
        ->willReturn($this->shippingAddressMock);


        $countryMock = $this->getMockBuilder(\Magento\Directory\Model\Country::class)
                ->disableOriginalConstructor()
                ->getMock();
        $countryMock->expects($this->any())
                    ->method('getName')
                    ->willReturn($countryName);
      
        $this->countryFactoryMock->expects($this->once())
        ->method('create')
        ->willReturn($countryMock);
        

        $result = $this->resolver->resolve(
            $this->getMockBuilder(Field::class)
                ->disableOriginalConstructor()
                ->getMock(),
            $this->createMock(ContextInterface::class),
            $this->createMock(ResolveInfo::class),
            $value
        );

        $expectedResult = [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'email' => $email,
            'mobile' => '+1-123-456-7890',
            'address' => [
                'street' => implode(', ', $streetName),
                'city' => $city,
                'region' => $region,
                'country' => $countryName
                    
            ]
        ];
        $this->assertEquals($expectedResult, $result);
     
        }

        public function testEmptyShippingAddressDataReturnsNull() {
            // Create a mock shipping address object with no data
    
            $orderNumber = '100000001';
            $firstName = 'John';
            $lastName = 'Doe';
            $email = 'john.doe@example.com';
            $countryId = 'IN';
            $countryName = 'India';
            $telephone = '+1-123-456-7890';
            $region = 'New York';
            $city = 'New York';
            $streetName = ['St. Nicholas Greek Orthodox Church'];
        
            $value = ['order_number' => $orderNumber];
            $this->orderMock->expects($this->once())
            ->method('loadByIncrementId')
            ->with($orderNumber)
            ->willReturnSelf();
            
            $this->shippingAddressMock->expects($this->any())
                             ->method('getData')
                             ->willReturn(array());
            $this->orderMock->expects($this->once())
            ->method('getShippingAddress')
            ->willReturn($this->shippingAddressMock);                
        
                             $result = $this->resolver->resolve(
                                $this->getMockBuilder(Field::class)
                                    ->disableOriginalConstructor()
                                    ->getMock(),
                                $this->createMock(ContextInterface::class),
                                $this->createMock(ResolveInfo::class),
                                $value
                            );
        
                            
            // Assert that the result is null
            $this->assertNull($result);
        }
}







