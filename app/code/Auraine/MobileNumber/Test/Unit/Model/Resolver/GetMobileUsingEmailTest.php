<?php

declare(strict_types=1);

namespace Auraine\MobileNumber\Test\Unit\Model\Resolver;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use PHPUnit\Framework\TestCase;
use Auraine\MobileNumber\Model\Resolver\GetMobileUsingEmail;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\Api\AttributeInterface ;
use Magento\Customer\Api\Data\CustomerInterface;

class GetMobileUsingEmailTest extends TestCase
{
    /**
     * @var CustomerRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $customerRepositoryMock;

    /**
     * @var GetMobileUsingEmail
     */
    private $resolver;

    protected function setUp(): void
    {
        $this->customerRepositoryMock = $this->createMock(CustomerRepositoryInterface::class);
        $this->resolver = new GetMobileUsingEmail($this->customerRepositoryMock);
    }

    public function testResolveReturnsMobileNumberIfCustomerExistsWithMobileNumber()
    {
        $email = 'test@example.com';
        $mobileNumber = '1234567890';
            
        $attributeMock = $this->createMock(AttributeInterface::class);
        $attributeMock->expects($this->any())
            ->method('getValue')
            ->willReturn($mobileNumber);
        
        $customerMock = $this->createMock(CustomerInterface::class);
        $customerMock->expects($this->any())
            ->method('getCustomAttribute')
            ->with('mobilenumber')
            ->willReturn($attributeMock);
        
        $customerRepositoryMock = $this->createMock(CustomerRepositoryInterface::class);
        $customerRepositoryMock->expects($this->once())
            ->method('get')
            ->with($email)
            ->willReturn($customerMock);
        
        $resolver = new GetMobileUsingEmail($customerRepositoryMock);

        $field = $this->createMock(Field::class);
        $context = $this->createMock(ContextInterface::class);
        $info = $this->createMock(ResolveInfo::class);
        $args = ['email' => $email];
        $result = $resolver->resolve($field, $context, $info, null, $args);

        $this->assertEquals($mobileNumber, $result);
    }

    public function testResolveReturnsNullIfCustomerExistsWithoutMobileNumber(): void
    {
        $email = 'test@example.com';
        $customerMock = $this->createMock(\Magento\Customer\Api\Data\CustomerInterface::class);
        $customerMock->method('getCustomAttribute')->with('mobilenumber')->willReturn(null);
        $this->customerRepositoryMock->method('get')->with($email)->willReturn($customerMock);

        $args = ['email' => $email]; // make sure the email argument is included
        $field = $this->createMock(Field::class);
        $context = $this->createMock(ContextInterface::class);
        $info = $this->createMock(ResolveInfo::class);
        $result = $this->resolver->resolve($field, $context, $info, null, $args);
        $this->assertNull($result);
    }

    public function testResolveThrowsGraphQlInputExceptionOnEmptyEmail(): void
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage('Invalid parameter list.');
    
        $field = $this->createMock(Field::class);
        $context = $this->createMock(ContextInterface::class);
        $info = $this->createMock(ResolveInfo::class);

        $result = $this->resolver->resolve($field, $context, $info, null, ['email' => '']);
        $this->assertNull($result);
    }
}
