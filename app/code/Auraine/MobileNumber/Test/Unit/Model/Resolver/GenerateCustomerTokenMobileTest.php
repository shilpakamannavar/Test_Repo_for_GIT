<?php

declare(strict_types=1);

namespace Auraine\MobileNumber\Test\Unit\Model\Resolver;

use Auraine\MobileNumber\Model\Resolver\GenerateCustomerTokenMobile;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Integration\Api\CustomerTokenServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class GenerateCustomerTokenMobileTest extends TestCase
{
    /**
     * @var CustomerTokenServiceInterface|MockObject
     */
    private $customerTokenService;

    /**
     * @var CustomerFactory|MockObject
     */
    private $customerFactory;

    /**
     * @var GenerateCustomerTokenMobile
     */
    private $resolver;

     /**
      * @var ObjectManager
      */
    private $objectManager;

    protected function setUp(): void
    {
        $this->customerTokenService = $this->createMock(CustomerTokenServiceInterface::class);
        $this->customerFactory = $this->createMock(CustomerFactory::class);
        $this->resolver = new GenerateCustomerTokenMobile($this->customerTokenService, $this->customerFactory);
        $this->objectManager = new ObjectManager($this);
    }

    public function testResolveThrowsExceptionIfMobileNotSpecified(): void
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage('Specify the "mobile" value.');

        $field = $this->createMock(Field::class);
        $context = $this->createMock(ContextInterface::class);
        $info = $this->createMock(ResolveInfo::class);
        $args = [];

        $this->resolver->resolve($field, $context, $info, null, $args);
    }

    public function testResolveThrowsExceptionIfPasswordNotSpecified(): void
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage('Specify the "password" value.');

        $field = $this->createMock(Field::class);
        $context = $this->createMock(ContextInterface::class);
        $info = $this->createMock(ResolveInfo::class);
        $args = ['mobile' => '+1234567890'];

        $this->resolver->resolve($field, $context, $info, null, $args);
    }

    public function testResolveThrowsExceptionIfInvalidMobile(): void
    {
        $this->expectException(GraphQlAuthenticationException::class);
        $this->expectExceptionMessage('Invalid number.');

        $field = $this->createMock(Field::class);
        $context = $this->createMock(ContextInterface::class);
        $info = $this->createMock(ResolveInfo::class);
        $args = ['mobile' => 'invalid', 'password' => 'password123'];

        $this->resolver->resolve($field, $context, $info, null, $args);
    }

    public function testResolveThrowsExceptionOnInvalidMobile(): void
    {
        $this->expectException(GraphQlAuthenticationException::class);
        $this->expectExceptionMessage('Invalid number.');
        $this->expectExceptionCode(0);

        $args = [
            'mobile' => '+123',
            'password' => 'testpassword'
        ];

        $field = $this->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();

        $context = $this->getMockBuilder(ContextInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $info = $this->getMockBuilder(ResolveInfo::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resolver = $this->objectManager->getObject(GenerateCustomerTokenMobile::class, [
            'customerTokenService' => $this->customerTokenService,
            'customerFactory' => $this->customerFactory
        ]);

        $resolver->resolve($field, $context, $info, null, $args);
    }
 
    public function invalidMobileProvider()
    {
        return [
            ['+123'],
            ['abc'],
            ['123'],
            ['1234567890123']
        ];
    }
    
    public function testResolveThrowsExceptionOnMissingMobile(): void
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage('Specify the "mobile" value.');

        $context = $this->createMock(ContextInterface::class);
        $field = $this->createMock(Field::class);
        $info = $this->createMock(ResolveInfo::class);
        $args = ['mobile' => '', 'password' => 'password123'];
        $this->resolver->resolve($field, $context, $info, null, $args);
    }

    public function testResolveThrowsExceptionOnMissingPassword(): void
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage('Specify the "password" value.');

        $context = $this->createMock(ContextInterface::class);
        $field = $this->createMock(Field::class);
        $info = $this->createMock(ResolveInfo::class);
        $args = ['mobile' => '+1234567890', 'password' => ''];
        $this->resolver->resolve($field, $context, $info, null, $args);
    }
}
