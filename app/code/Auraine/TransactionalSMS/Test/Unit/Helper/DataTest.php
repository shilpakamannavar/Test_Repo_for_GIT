<?php
namespace Auraine\TransactionalSMS\Test\Unit\Helper;

use Auraine\TransactionalSMS\Helper\Data;
use Magecomp\Mobilelogin\Helper\Data as MobileloginHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Sales\Model\Order;
use PHPUnit\Framework\TestCase;

/**
 * Summary of DataTest
 */
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
    public function testDispachSMS($mobileNumber, $otpStatus, $expectedMobileNumber)
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
     * Test orderSuccessSMS() method with valid mobile and disabled SMS.
     */
    public function testOrderSuccessSMSWithValidMobileAndDisabledSMS()
    {
        $configPath = 'transaction_sms_control/transaction_sms/enable_sms';
        $mobile = '913456789012'; // 12 digits mobile number
        $deliveryDate = '16/03/2023';
        $orderId = 000001234;

        $this->scopeConfigMock->expects($this->any())
            ->method('getValue')
            ->with($configPath)
            ->willReturn(false);

        $this->mobileloginHelperMock->expects($this->never())
            ->method('callApiUrl');

        $this->helper->orderSuccessSMS($configPath, $mobile, $deliveryDate, $orderId);
    }

    /**
     * Test transactionSMS() method with valid mobile and disabled SMS.
     */
    public function testTransactionSMSWithValidMobileAndDisabledSMS()
    {
        $configPath = 'transaction_sms_control/transaction_sms/enable_sms';
        $mobile = '913456789012'; // 12 digits mobile number
        $grandTotal = 200;
        $orderId = 000001234;

        $this->scopeConfigMock->expects($this->any())
            ->method('getValue')
            ->with($configPath)
            ->willReturn(false);

        $this->mobileloginHelperMock->expects($this->never())
            ->method('callApiUrl');

        $this->helper->transactionSMS($configPath, $mobile, $grandTotal, $orderId);
    }

    /**
     * Test shipmentNotDelivered() method with valid mobile and disabled SMS.
     */
    public function testShipmentNotDeliveredWithValidMobileAndDisabledSMS()
    {
        $configPath = 'transaction_sms_control/transaction_sms/enable_sms';
        $mobile = '913456789012'; // 12 digits mobile number
        $orderId = 000001234;

        $this->scopeConfigMock->expects($this->any())
            ->method('getValue')
            ->with($configPath)
            ->willReturn(false);

        $this->mobileloginHelperMock->expects($this->never())
            ->method('callApiUrl');

        $this->helper->shipmentNotDelivered($configPath, $mobile, $orderId);
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

     /**
     * Test customerAbandonedCartSMS() method with valid mobile and disabled SMS.
     */
    public function testCustomerAbandonedCartSMSWithValidMobileAndDisabledSMS()
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

        $this->helper->customerAbandonedCartSMS($configPath, $mobile, $customerName);
    }

    public function testCustomerAbandonedCartSMSDoesNotDispatchSMSWhenOtpStatusIsOff()
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

        $dataHelper->customerAbandonedCartSMS(
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

    public function testCustomerAbandonedCartSMSDoesNotDispatchSMSWhenMobileNumberIsInvalid()
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

        $dataHelper->customerAbandonedCartSMS(Data::OTP_STATUS_PATH, $mobile, $customerName);
    }

    /**
     * testShipmentShippedSMS
     *
     * @return void
     */
    public function testShipmentShippedSMS()
    {
        $configPath = 'transaction_sms_control/transaction_sms/enable_sms';
        $orderId = 123;
        $mobile = '1234567890';
        $quantity = 2;
        $description = 'Test product';

        $order = $this->createMock(Order::class);

        $order->expects($this->once())
            ->method('getIncrementId')
            ->willReturn($orderId);

        $shippingAddress = $this->createMock(\Magento\Sales\Model\Order\Address::class);

        $order->expects($this->once())
            ->method('getShippingAddress')
            ->willReturn($shippingAddress);

        $shippingAddress->expects($this->once())
            ->method('getTelephone')
            ->willReturn($mobile);

        $order->expects($this->any())
            ->method('getTotalItemCount')
            ->willReturn($quantity);

        $orderItem = $this->createMock(\Magento\Sales\Model\Order\Item::class);

        $order->expects($this->once())
            ->method('getAllItems')
            ->willReturn([$orderItem]);

        $orderItem->expects($this->once())
            ->method('getName')
            ->willReturn($description);

        $this->scopeConfigMock->expects($this->any())
            ->method('getValue')
            ->with($configPath)
            ->willReturn(false);

        $this->mobileloginHelperMock->expects($this->never())
            ->method('callApiUrl');

        $this->helper->shipmentShippedSMS(Data::OTP_STATUS_PATH, $order);
    }

    /**
     * testOrderDeliveredSMS
     * @return void
     */
    public function testOrderDeliveredSMS()
    {
        $this->privateTestLogic('orderDeliveredSMS');
    }

    /**
     * testshipmentCancelledSMS
     * @return void
     */
    public function testShipmentCancelledSMS()
    {
        $this->privateTestLogic('shipmentCancelledSMS');
    }

    public function testReturnInitiatedSMS()
    {
        $this->privateTestLogic('returnInitiatedSMS');
    }

    public function privateTestLogic($functionName)
    {
        $configPath = 'transaction_sms_control/transaction_sms/enable_sms';
        $orderId = 123;
        $mobile = '1234567890';
        $quantity = 2;
        $description = 'Test product';

        $order = $this->createMock(Order::class);

        $order->expects($this->once())
            ->method('getIncrementId')
            ->willReturn($orderId);

        $order->expects($this->any())
            ->method('getTotalItemCount')
            ->willReturn($quantity);

        $orderItem = $this->createMock(\Magento\Sales\Model\Order\Item::class);

        $order->expects($this->once())
            ->method('getAllItems')
            ->willReturn([$orderItem]);

        $orderItem->expects($this->once())
            ->method('getName')
            ->willReturn($description);

        $this->scopeConfigMock->expects($this->any())
            ->method('getValue')
            ->with($configPath)
            ->willReturn(false);

        $this->mobileloginHelperMock->expects($this->never())
            ->method('callApiUrl');

        $this->helper->{$functionName}(Data::OTP_STATUS_PATH, $mobile, $order);
    }

}
