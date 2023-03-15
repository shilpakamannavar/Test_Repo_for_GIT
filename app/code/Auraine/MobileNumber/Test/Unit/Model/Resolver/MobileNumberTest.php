<?php

declare(strict_types=1);

namespace Auraine\MobileNumber\Test\Unit\Model\Resolver;

use Auraine\MobileNumber\Model\Resolver\MobileNumber;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface as ResolverContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\IdentityInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MobileNumberTest extends TestCase
{
    /**
     * @var MobileNumber
     */
    private $resolver;

    /**
     * @var GetCustomer|MockObject
     */
    private $getCustomerMock;

    /**
     * @var ResolverContextInterface|MockObject
     */
    private $contextMock;

    /**
     * @var Field|MockObject
     */
    private $fieldMock;

    /**
     * @var ResolveInfo|MockObject
     */
    private $resolveInfoMock;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->getCustomerMock = $this->getMockBuilder(GetCustomer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock = $this->getMockBuilder(ResolverContextInterface::class)
            ->getMock();

        $this->fieldMock = $this->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resolveInfoMock = $this->getMockBuilder(ResolveInfo::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resolver = $objectManager->getObject(
            MobileNumber::class,
            [
                'getCustomer' => $this->getCustomerMock,
            ]
        );
    }

    /**
     * @dataProvider customerDataProvider
     */
    public function testResolveReturnsMobileNumberIfCustomerExistsWithMobileNumber(CustomerInterface $customer)
    {
        $customerMobileNumber = '1234567890';
        $customer->setCustomAttribute('mobilenumber', $customerMobileNumber);

        $this->getCustomerMock->expects($this->once())
            ->method('execute')
            ->with($this->contextMock)
            ->willReturn($customer);

        $result = $this->resolver->resolve($this->fieldMock, $this->contextMock, $this->resolveInfoMock);

        $this->assertEquals($customerMobileNumber, $result);
    }

    /**
     * @dataProvider customerDataProvider
     */
    public function testResolveReturnsNullIfCustomerExistsWithoutMobileNumber(CustomerInterface $customer)
    {
        $customer->unsetCustomAttribute('mobilenumber');

        $this->getCustomerMock->expects($this->once())
            ->method('execute')
            ->with($this->contextMock)
            ->willReturn($customer);

        $result = $this->resolver->resolve($this->fieldMock, $this->contextMock, $this->resolveInfoMock);

        $this->assertNull($result);
    }

    public function testResolveThrowsExceptionIfCustomerIsNotAuthorized()
    {
        $this->expectException(GraphQlAuthorizationException::class);
        $this->expectExceptionMessage('The current customer isn\'t authorized.');

        $this->contextMock->method('getExtensionAttributes')
            ->willReturn(null);

        $this->resolver->resolve($this->fieldMock, $this->contextMock, $this->resolveInfoMock);
    }

    public function testResolveThrowsExceptionIfGetCustomerThrowsException()
    {
        $exceptionMessage = 'Some exception message';

        $this->getCustomerMock->expects($this->once())
            ->method('execute')
            ->willThrowException(new \Exception($exceptionMessage));

        $this->expectException(GraphQlAuthorizationException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $this->resolver->resolve($this->fieldMock, $this->contextMock, $this->resolveInfoMock);
    }

    public function customerDataProvider()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
    
        $customerWithMobileNumber = $objectManager->create(CustomerInterface::class);
        $customerWithMobileNumber->setId(1);
        $customerWithMobileNumber->setCustomAttribute('mobilenumber', '1234567890');
    
        $customerWithoutMobileNumber = $objectManager->create(CustomerInterface::class);
        $customerWithoutMobileNumber->setId(2);
    
        return [
            [$customerWithMobileNumber, '1234567890'],
            [$customerWithoutMobileNumber, null],
        ];
    }
}
