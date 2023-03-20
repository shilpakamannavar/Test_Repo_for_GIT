<?php

namespace Auraine\ExtendedCustomerGraphQLException\Plugin;

use Magento\Integration\Api\CustomerTokenServiceInterface;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use PHPUnit\Framework\TestCase;

/**
 * Summary of GenerateCustomerTokenPluginTest
 */
class GenerateCustomerTokenPluginTest extends TestCase
{

    /**
     * CONST EMAIL
     */
    public const EMAIL = 'test@example.com';

    /**
     * Summary of testGenerateCustomerTokenWithValidEmailAndPassword
     * @return void
     */
    public function testGenerateCustomerTokenWithValidEmailAndPassword()
    {
        $customerTokenServiceMock = $this->createMock(CustomerTokenServiceInterface::class);
        $customerTokenServiceMock->expects($this->once())
            ->method('createCustomerAccessToken')
            ->with(self::EMAIL, 'password')
            ->willReturn('some_token');

        $generateCustomerTokenPlugin = new GenerateCustomerTokenPlugin($customerTokenServiceMock);

        $result = $generateCustomerTokenPlugin->aroundResolve(
            $this->createMock(\Magento\CustomerGraphQl\Model\Resolver\GenerateCustomerToken::class),
            function () {
                return null;
            },
            $this->createMock(Field::class),
            null,
            $this->createMock(ResolveInfo::class),
            null,
            ['email' => self::EMAIL, 'password' => 'password']
        );

        $this->assertEquals(['token' => 'some_token'], $result);
    }

    /**
     * Summary of testGenerateCustomerTokenWithMissingEmail
     * @return void
     */
    public function testGenerateCustomerTokenWithMissingEmail()
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage('Specify the "email" value.');

        $generateCustomerTokenPlugin = new GenerateCustomerTokenPlugin(
            $this->createMock(CustomerTokenServiceInterface::class)
        );

        $generateCustomerTokenPlugin->aroundResolve(
            $this->createMock(\Magento\CustomerGraphQl\Model\Resolver\GenerateCustomerToken::class),
            function () {
                return null;
            },
            $this->createMock(Field::class),
            null,
            $this->createMock(ResolveInfo::class),
            null,
            ['password' => 'password']
        );
    }

    /**
     * Summary of testGenerateCustomerTokenWithMissingPassword
     * @return void
     */
    public function testGenerateCustomerTokenWithMissingPassword()
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage('Specify the "password" value.');

        $generateCustomerTokenPlugin = new GenerateCustomerTokenPlugin(
            $this->createMock(CustomerTokenServiceInterface::class)
        );

        $generateCustomerTokenPlugin->aroundResolve(
            $this->createMock(\Magento\CustomerGraphQl\Model\Resolver\GenerateCustomerToken::class),
            function () {
                return null;
            },
            $this->createMock(Field::class),
            null,
            $this->createMock(ResolveInfo::class),
            null,
            ['email' => self::EMAIL]
        );
    }

    /**
     * Summary of testGenerateCustomerTokenWithInvalidCredentials
     * @return void
     */
    public function testGenerateCustomerTokenWithInvalidCredentials()
    {
        $this->expectException(GraphQlAuthenticationException::class);
        $this->expectExceptionMessage('Invalid Login or Password.Please try again');

        $customerTokenServiceMock = $this->createMock(CustomerTokenServiceInterface::class);
        $customerTokenServiceMock->expects($this->once())
            ->method('createCustomerAccessToken')
            ->willThrowException(new AuthenticationException(__('Invalid login or password.')));

        $generateCustomerTokenPlugin = new GenerateCustomerTokenPlugin($customerTokenServiceMock);

        $generateCustomerTokenPlugin->aroundResolve(
            $this->createMock(\Magento\CustomerGraphQl\Model\Resolver\GenerateCustomerToken::class),
            function () {
                return null;
            },
            $this->createMock(Field::class),
            null,
            $this->createMock(ResolveInfo::class),
            null,
            ['email' => self::EMAIL, 'password' => 'password']
        );
    }
}
