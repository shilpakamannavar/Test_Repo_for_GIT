<?php
namespace Auraine\TransactionalSMS\Test\Unit\Observer;

use Auraine\TransactionalSMS\Helper\Data;
use Auraine\TransactionalSMS\Observer\SendSMSOnOrderStatusChange;
use Magento\Framework\Event\Observer;
use Magento\Framework\Model\AbstractModel;
use Magento\Sales\Model\Order;
use PHPUnit\Framework\TestCase;

/**
 * Summary of SendSMSOnOrderStatusChangeTest
 */
class SendSMSOnOrderStatusChangeTest extends TestCase
{
    /**
     * @var Data
     */
    private $helperDataMock;

    /**
     * @var Order
     */
    private $orderMock;

    /**
     * @var Observer
     */
    private $observerMock;

    /**
     * Summary of setUp
     * @return void
     */
    protected function setUp(): void
    {
        $this->helperDataMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->orderMock = $this->getMockBuilder(Order::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->observerMock = $this->getMockBuilder(Observer::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Summary of testExecuteWithNullMobile
     * @return void
     */
    public function testExecuteWithNullMobile()
    {
        $this->orderMock->expects($this->once())
            ->method('getShippingAddress')
            ->willReturnSelf();
        $this->orderMock->expects($this->once())
            ->method('__call')
            ->with('getTelephone')
            ->willReturn(null);
        $this->observerMock->expects($this->once())
            ->method('getEvent')
            ->willReturnSelf();
        $this->observerMock->expects($this->once())
            ->method('__call')
            ->with('getOrder')
            ->willReturn($this->orderMock);
        $sendSMSOnOrderStatusChange = new SendSMSOnOrderStatusChange($this->helperDataMock);
        $this->assertSame($sendSMSOnOrderStatusChange, $sendSMSOnOrderStatusChange->execute($this->observerMock));
    }

    /**
     * Summary of testExecuteWithModelOrder
     * @return void
     */
    public function testExecuteWithModelOrder()
    {
        $this->orderMock->expects($this->once())
            ->method('getShippingAddress')
            ->willReturnSelf();
        $this->orderMock->expects($this->once())
            ->method('__call')
            ->with('getTelephone')
            ->willReturn('1234567890');
        $this->orderMock->expects($this->once())
            ->method('getState')
            ->willReturn(Order::STATE_PROCESSING);
        $this->observerMock->expects($this->once())
            ->method('getEvent')
            ->willReturnSelf();
        $this->observerMock->expects($this->once())
            ->method('__call')
            ->with('getOrder')
            ->willReturn($this->orderMock);
        $this->helperDataMock->expects($this->once())
            ->method('shipmentShippedSMS')
            ->with("transaction_sms_control/shipped_sms/message", $this->orderMock);

        $sendSMSOnOrderStatusChange = new SendSMSOnOrderStatusChange($this->helperDataMock);

        $this->assertSame($sendSMSOnOrderStatusChange, $sendSMSOnOrderStatusChange->execute($this->observerMock));
    }

    /**
     * Summary of testExecuteOrderDeliveredSMS
     * @return void
     */
    public function testExecuteOrderDeliveredSMS()
    {
        $this->privateTestLogic(Order::STATE_COMPLETE);

        $this->helperDataMock->expects($this->once())
            ->method('orderDeliveredSMS')
            ->with("transaction_sms_control/delivered_sms/message", '1234567890', $this->orderMock);

        $sendSMSOnOrderStatusChange = new SendSMSOnOrderStatusChange($this->helperDataMock);

        $this->assertSame($sendSMSOnOrderStatusChange, $sendSMSOnOrderStatusChange->execute($this->observerMock));
    }

    /**
     * Summary of testExecuteShipmentNotDelivered
     * @return void
     */
    public function testExecuteShipmentNotDelivered()
    {
        $this->privateTestLogic(Order::STATE_CANCELED);

        $this->helperDataMock->expects($this->once())
            ->method('shipmentNotDelivered')
            ->with("transaction_sms_control/not_delivered_sms/message", '1234567890', null);

        $sendSMSOnOrderStatusChange = new SendSMSOnOrderStatusChange($this->helperDataMock);

        $this->assertSame($sendSMSOnOrderStatusChange, $sendSMSOnOrderStatusChange->execute($this->observerMock));
    }

    /**
     * Summary of testExecuteShipmentCancelledSMS
     * @return void
     */
    public function testExecuteShipmentCancelledSMS()
    {
        $this->privateTestLogic(Order::STATE_HOLDED);

        $this->helperDataMock->expects($this->once())
            ->method('shipmentCancelledSMS')
            ->with("transaction_sms_control/cancelled_sms/message", '1234567890', $this->orderMock);

        $sendSMSOnOrderStatusChange = new SendSMSOnOrderStatusChange($this->helperDataMock);

        $this->assertSame($sendSMSOnOrderStatusChange, $sendSMSOnOrderStatusChange->execute($this->observerMock));
    }

    /**
     * Summary of testExecuteReturnInitiatedSMS
     * @return void
     */
    public function testExecuteReturnInitiatedSMS()
    {
        $this->privateTestLogic("return");

        $this->helperDataMock->expects($this->once())
            ->method('returnInitiatedSMS')
            ->with("transaction_sms_control/return_inititated_sms/message", '1234567890', $this->orderMock);

        $sendSMSOnOrderStatusChange = new SendSMSOnOrderStatusChange($this->helperDataMock);

        $this->assertSame($sendSMSOnOrderStatusChange, $sendSMSOnOrderStatusChange->execute($this->observerMock));
    }

    /**
     * Summary of testExecuteTransactionSMSPicked
     * @return void
     */
    public function testExecuteTransactionSMSPicked()
    {
        $this->privateTestLogic("picked");

        $this->helperDataMock->expects($this->once())
            ->method('transactionSMS')
            ->with("transaction_sms_control/return_picked_sms/message", '1234567890', ' ', null);

        $sendSMSOnOrderStatusChange = new SendSMSOnOrderStatusChange($this->helperDataMock);

        $this->assertSame($sendSMSOnOrderStatusChange, $sendSMSOnOrderStatusChange->execute($this->observerMock));
    }

    /**
     * Summary of testExecuteTransactionSMSRefunded
     * @return void
     */
    public function testExecuteTransactionSMSRefunded()
    {
        $this->privateTestLogic("refunded");

        $this->helperDataMock->expects($this->once())
            ->method('transactionSMS')
            ->with("transaction_sms_control/refund_success/message", '1234567890', null, null);

        $sendSMSOnOrderStatusChange = new SendSMSOnOrderStatusChange($this->helperDataMock);

        $this->assertSame($sendSMSOnOrderStatusChange, $sendSMSOnOrderStatusChange->execute($this->observerMock));
    }

    /**
     * testExecuteDefault
     * @return void
     */
    public function testExecuteDefault()
    {
        $this->privateTestLogic(null);

        $sendSMSOnOrderStatusChange = new SendSMSOnOrderStatusChange($this->helperDataMock);

        $this->assertSame($sendSMSOnOrderStatusChange, $sendSMSOnOrderStatusChange->execute($this->observerMock));
    }

    /**
     * Summary of privateTestLogic
     * @param mixed $status
     * @return void
     */
    private function privateTestLogic($status)
    {
        $this->orderMock->expects($this->once())
            ->method('getShippingAddress')
            ->willReturnSelf();

        $this->orderMock->expects($this->once())
            ->method('__call')
            ->with('getTelephone')
            ->willReturn('1234567890');

        $this->orderMock->expects($this->once())
            ->method('getState')
            ->willReturn($status);

        $this->observerMock->expects($this->once())
            ->method('getEvent')
            ->willReturnSelf();

        $this->observerMock->expects($this->once())
            ->method('__call')
            ->with('getOrder')
            ->willReturn($this->orderMock);
    }

}