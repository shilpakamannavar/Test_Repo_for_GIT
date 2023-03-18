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
     * const MOBILE
     */
    private const MOBILE = '1234567890';

    /**
     * const CUSTOMER_NAME
     */
    private const CUSTOMER_NAME = 'John Doe';

    /**
     * const CONFIG_PATH
     */
    private const CONFIG_PATH = 'transaction_sms_control/transaction_sms/enable_sms';

    /**
     * CONST SOME_PATH
     */
    private const SOME_PATH = 'some/path';

    /**
     * const HELLO_NAME
     */
    private const HELLO_NAME = 'Hello {{name}}';

    /**
     * const NAME
     */
    private const NAME = '{{name}}';

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
            [self::SOME_PATH, 'value'],
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

    /**
     * Summary of generateMessageDataProvider
     * @return array
     */
    public function generateMessageDataProvider()
    {
        return [
            [[], [], self::SOME_PATH, 'Hello', 'Hello'],
            [[], [], self::SOME_PATH, self::HELLO_NAME, self::HELLO_NAME],
            [[self::NAME], ['John'], self::SOME_PATH, self::HELLO_NAME, 'Hello John'],
            [[self::NAME], ['John'], self::SOME_PATH, 'Hello {{name}} {{age}}', 'Hello John {{age}}'],
            [[self::NAME, '{{age}}'], ['John', '25'], self::SOME_PATH, 'Hello {{name}} {{age}}', 'Hello John 25'],
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

    /**
     * Summary of mobileNumberProvider
     * @return array
     */
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
        $deliveryDate = '16/03/2023';
        $orderId = 000001234;

        $this->scopeConfigMock->expects($this->any())
            ->method('getValue')
            ->with(self::CONFIG_PATH)
            ->willReturn(false);

        $this->mobileloginHelperMock->expects($this->never())
            ->method('callApiUrl');

        $this->helper->orderSuccessSMS(self::CONFIG_PATH, self::MOBILE, $deliveryDate, $orderId);
    }

    /**
     * Test transactionSMS() method with valid mobile and disabled SMS.
     */
    public function testTransactionSMSWithValidMobileAndDisabledSMS()
    {
        $mobile = '913456789012'; // 12 digits mobile number
        $grandTotal = 200;
        $orderId = 000001234;

        $this->scopeConfigMock->expects($this->any())
            ->method('getValue')
            ->with(self::CONFIG_PATH)
            ->willReturn(false);

        $this->mobileloginHelperMock->expects($this->never())
            ->method('callApiUrl');

        $this->helper->transactionSMS(self::CONFIG_PATH, $mobile, $grandTotal, $orderId);
    }

    /**
     * Test shipmentNotDelivered() method with valid mobile and disabled SMS.
     */
    public function testShipmentNotDeliveredWithValidMobileAndDisabledSMS()
    {
        $mobile = '913456789012'; // 12 digits mobile number
        $orderId = 000001234;

        $this->scopeConfigMock->expects($this->any())
            ->method('getValue')
            ->with(self::CONFIG_PATH)
            ->willReturn(false);

        $this->mobileloginHelperMock->expects($this->never())
            ->method('callApiUrl');

        $this->helper->shipmentNotDelivered(self::CONFIG_PATH, $mobile, $orderId);
    }

    /**
     * Summary of testCustomerRegisterSuccessSMSDoesNotDispatchSMSWhenOtpStatusIsOff
     * @return void
     */
    public function testCustomerRegisterSuccessSMSDoesNotDispatchSMSWhenOtpStatusIsOff()
    {
        $dataHelper = new Data($this->mobileloginHelperMock, $this->scopeConfigMock);

        $this->scopeConfigMock->expects($this->any())
            ->method('getValue')
            ->with($this->equalTo(Data::OTP_STATUS_PATH))
            ->willReturn(false);

        $this->mobileloginHelperMock->expects($this->never())
            ->method('callApiUrl');

        $dataHelper->customerRegisterSuccessSMS(
            Data::OTP_STATUS_PATH,
            self::MOBILE,
            self::CUSTOMER_NAME
        );
    }

     /**
     * Test customerAbandonedCartSMS() method with valid mobile and disabled SMS.
     */
    public function testCustomerAbandonedCartSMSWithValidMobileAndDisabledSMS()
    {
        $this->scopeConfigMock->expects($this->any())
            ->method('getValue')
            ->with(self::CONFIG_PATH)
            ->willReturn(false);

        $this->mobileloginHelperMock->expects($this->never())
            ->method('callApiUrl');

        $this->helper->customerAbandonedCartSMS(
            self::CONFIG_PATH,
            self::MOBILE,
            self::CUSTOMER_NAME
        );
    }

    /**
     * Summary of testCustomerAbandonedCartSMSDoesNotDispatchSMSWhenOtpStatusIsOff
     * @return void
     */
    public function testCustomerAbandonedCartSMSDoesNotDispatchSMSWhenOtpStatusIsOff()
    {
        $dataHelper = new Data($this->mobileloginHelperMock, $this->scopeConfigMock);

        $this->scopeConfigMock->expects($this->any())
            ->method('getValue')
            ->with($this->equalTo(Data::OTP_STATUS_PATH))
            ->willReturn(false);

        $this->mobileloginHelperMock->expects($this->never())
            ->method('callApiUrl');

        $dataHelper->customerAbandonedCartSMS(
            Data::OTP_STATUS_PATH,
            self::MOBILE,
            self::CUSTOMER_NAME
        );
    }

    /**
     * testShipmentShippedSMS
     *
     * @return void
     */
    public function testShipmentShippedSMS()
    {
        $orderId = 123;
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
            ->willReturn(self::MOBILE);

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
            ->with(self::CONFIG_PATH)
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

    /**
     * Summary of testReturnInitiatedSMS
     * @return void
     */
    public function testReturnInitiatedSMS()
    {
        $this->privateTestLogic('returnInitiatedSMS');
    }

    /**
     * Summary of privateTestLogic
     * @param mixed $functionName
     * @return void
     */
    public function privateTestLogic($functionName)
    {
        $orderId = 123;
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
            ->with(self::CONFIG_PATH)
            ->willReturn(false);

        $this->mobileloginHelperMock->expects($this->never())
            ->method('callApiUrl');

        $this->helper->{$functionName}(Data::OTP_STATUS_PATH, self::MOBILE, $order);
    }

}
