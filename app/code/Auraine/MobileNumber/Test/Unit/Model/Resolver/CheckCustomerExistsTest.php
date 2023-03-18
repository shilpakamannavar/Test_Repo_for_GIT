<?php
namespace Auraine\MobileNumber\Test\Unit\Model\Resolver;

use PHPUnit\Framework\TestCase;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Auraine\MobileNumber\Model\Resolver\CheckCustomerExists;
use Alternativetechlab\Mobilelogin\Helper\Data;
use Magento\Framework\GraphQl\Config\Element\Field;

class CheckCustomerExistsTest extends TestCase
{
    /**
     * Helper Data Mock instance
     *
     * @var Data
     */
    protected $helperDataMock;
    /**
     * Context Mock instance
     *
     * @var contextMock
     */
    protected $contextMock;
    /**
     * Resolve Info instance
     *
     * @var ResolveInfo
     */
    protected $resolveInfoMock;
    /**
     * Field instance
     *
     * @var Field
     */
    protected $fieldMock;
    /**
     * Resolver instance
     *
     * @var resolver
     */
    private $resolver;
    /**
     * Main setUp method for the test
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->helperDataMock = $this->createMock(Data::class);
        $this->contextMock = $this->createMock(ContextInterface::class);
        $this->resolveInfoMock = $this->createMock(ResolveInfo::class);
        $this->fieldMock = $this->createMock(Field::class);
      
        $this->resolver = new CheckCustomerExists(
            $this->helperDataMock,
            $this->contextMock,
            $this->fieldMock
        );
    }
    /**
     * Test for  Resolve With Invalid Parameters
     */
    public function testResolveWithInvalidParameters()
    {
        $resolver = new CheckCustomerExists($this->helperDataMock);
        $this->expectException(GraphQlInputException::class);
        $resolver->resolve($this->fieldMock, $this->contextMock, $this->resolveInfoMock, null, [
            'field_value' => null,
            'type' => 'some_invalid_type',
        ]);
    }

    public function testResolveWithInvalidMobileNumber()
    {
        $resolver = new CheckCustomerExists($this->helperDataMock);
        $this->expectException(GraphQlAuthenticationException::class);
        $resolver->resolve($this->fieldMock, $this->contextMock, $this->resolveInfoMock, null, [
            'field_value' => 'abc567@#zzz000',
            'type' => 'mobile',
        ]);
    }

    public function testResolveWithNonexistentCustomer()
    {
        $this->helperDataMock->expects($this->once())
            ->method('checkCustomerExists')
            ->with('918967543200', 'mobile')
            ->willReturn([]);
        $resolver = new CheckCustomerExists($this->helperDataMock);
        $result = $resolver->resolve($this->fieldMock, $this->contextMock, $this->resolveInfoMock, null, [
            'field_value' => '918967543200',
            'type' => 'mobile',
        ]);
        $this->assertFalse($result);
    }

    public function testResolveWithExistentCustomer()
    {
        $this->helperDataMock->expects($this->once())
            ->method('checkCustomerExists')
            ->with('919898979678', 'mobile')
            ->willReturn(['some_customer']);
        $resolver = new CheckCustomerExists($this->helperDataMock);
        $result = $resolver->resolve($this->fieldMock, $this->contextMock, $this->resolveInfoMock, null, [
            'field_value' => '919898979678',
            'type' => 'mobile',
        ]);
        $this->assertTrue($result);
    }

    public function testValidateMobileWithInvalidMobileNumber()
    {
        $resolver = new CheckCustomerExists($this->helperDataMock);
        $result = $resolver->validateMobile('abcd0098%^&');
        $this->assertFalse($result);
    }

    public function testValidateMobileWithValidMobileNumber()
    {
        $resolver = new CheckCustomerExists($this->helperDataMock);
        $result = $resolver->validateMobile('919898989898');
        $this->assertTrue($result);
    }

    public function inputExceptionDataProvider(): array
    {
        return [
            [
                [],
                'Invalid parameter list.'
            ],
            [
                ['field_value' => '1234567890'],
                'Invalid parameter list.'
            ],
            [
                ['type' => 'mobile'],
                'Invalid parameter list.'
            ],
        ];
    }
}
