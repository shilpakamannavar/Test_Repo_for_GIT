<?php
namespace Auraine\TransactionalSMS\Test\Unit\Helper;

use Auraine\TransactionalSMS\Helper\Data;
use GraphQL\Mutation;
use Magecomp\Mobilelogin\Helper\Data as MobileloginHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * @var MobileloginHelper|\PHPUnit\Framework\MockObject\MockObject
     */
    private $mobileloginHelperMock;

    /**
     * @var ScopeConfigInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $scopeConfigMock;

    protected function setUp(): void
    {
        $this->mobileloginHelperMock = $this->getMockBuilder(MobileloginHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->getMock();

        $this->helper = new Data($this->mobileloginHelperMock, $this->scopeConfigMock);
    }

    /**
     * @dataProvider getConfigValueDataProvider
     */
    public function testGetConfigValue($path, $value)
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with($path, ScopeInterface::SCOPE_STORE)
            ->willReturn($value);

        $result = $this->helper->getConfigValue($path);

        $this->assertSame($value, $result);
    }

    public function getConfigValueDataProvider()
    {
        return [
            ['some/path', 'value'],
            ['another/path', 123],
            ['yet/another/path', null],
        ];
    }

    /**
     * @dataProvider generateMessageDataProvider
     */
    public function testGenerateMessage($codes, $accurate, $configPath, $configValue, $expectedResult)
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with($configPath, ScopeInterface::SCOPE_STORE)
            ->willReturn($configValue);

        $result = $this->helper->generateMessage($codes, $accurate, $configPath);

        $this->assertSame($expectedResult, $result);
    }

    public function generateMessageDataProvider()
    {
        return [
            [[], [], 'some/path', 'Hello', 'Hello'],
            [[], [], 'some/path', 'Hello {{name}}', 'Hello {{name}}'],
            [['{{name}}'], ['John'], 'some/path', 'Hello {{name}}', 'Hello John'],
            [['{{name}}'], ['John'], 'some/path', 'Hello {{name}} {{age}}', 'Hello John {{age}}'],
            [['{{name}}', '{{age}}'], ['John', '25'], 'some/path', 'Hello {{name}} {{age}}', 'Hello John 25'],
        ];
    }

      /**
       * @dataProvider mobileNumberProvider
       */
    public function testDispatchSMS($mobileNumber, $otpStatus, $expectedMobileNumber)
    {
        $message = 'Test message';

        $this->scopeConfigMock->expects($this->once())
                          ->method('getValue')
                          ->with(Data::OTP_STATUS_PATH)
                          ->willReturn($otpStatus);

        $this->mobileloginHelperMock->expects($this->once())
                         ->method('callApiUrl')
                         ->with($message, $expectedMobileNumber, 1);

        $this->helper->dispachSMS($message, $mobileNumber);
    }

    public function mobileNumberProvider()
    {
        return [
            ['9876543210', true, '919876543210'],
            ['123456789012', true, '123456789012']
        ];
    }

    /**
     * Test customerRegisterSuccessSMS() method with valid mobile and disabled SMS.
     */
    public function testCustomerRegisterSuccessSMSWithValidMobileAndDisabledSMS()
    {
        $configPath = 'transaction_sms_control/transaction_sms/enable_sms';
        $mobile = '913456789012'; // 12 digits mobile number
        $customerName = 'John Doe';
        $message = 'Welcome, John Doe!';

        $this->scopeConfigMock->expects($this->any())
            ->method('getValue')
            ->with($configPath)
            ->willReturn(false);

        $this->mobileloginHelperMock->expects($this->never())
            ->method('callApiUrl');

        $this->helper->customerRegisterSuccessSMS($configPath, $mobile, $customerName);
    }

    public function testCustomerRegisterSuccessSMSDoesNotDispatchSMSWhenOtpStatusIsOff()
    {
        $dataHelper = new Data($this->mobileloginHelperMock, $this->scopeConfigMock);

        $mobile = '1234567890';
        $customerName = 'John Doe';

        $this->scopeConfigMock->expects($this->any())
            ->method('getValue')
            ->with($this->equalTo(Data::OTP_STATUS_PATH))
            ->willReturn(false);

        $this->mobileloginHelperMock->expects($this->never())
            ->method('callApiUrl');

        $dataHelper->customerRegisterSuccessSMS(
            Data::OTP_STATUS_PATH,
            $mobile,
            $customerName
        );
    }

    public function testCustomerRegisterSuccessSMSDoesNotDispatchSMSWhenMobileNumberIsInvalid()
    {
        $dataHelper = new Data($this->mobileloginHelperMock, $this->scopeConfigMock);

        $mobile = '123456';
        $customerName = 'John Doe';

        $this->scopeConfigMock->expects($this->any())
            ->method('getValue')
            ->with(Data::OTP_STATUS_PATH, ScopeInterface::SCOPE_STORE)
            ->willReturn(true);

        $this->mobileloginHelperMock->expects($this->never())
            ->method('callApiUrl');

        $dataHelper->customerRegisterSuccessSMS(Data::OTP_STATUS_PATH, $mobile, $customerName);
    }
}
