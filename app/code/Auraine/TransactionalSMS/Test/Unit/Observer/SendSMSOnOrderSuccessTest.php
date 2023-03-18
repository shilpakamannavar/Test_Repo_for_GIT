<?php
namespace Auraine\TransactionalSMS\Test\Unit\Observer;

use Auraine\TransactionalSMS\Helper\Data;
use Auraine\TransactionalSMS\Observer\SendSMSOnOrderSuccess;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order;
use PHPUnit\Framework\TestCase;

class SendSMSOnOrderSuccessTest extends TestCase
{
    /**
     * @var Data|\PHPUnit\Framework\MockObject\MockObject
     */
    private $helperDataMock;

    /**
     * @var Order|\PHPUnit\Framework\MockObject\MockObject
     */
    private $orderMock;

    /**
     * @var Observer|\PHPUnit\Framework\MockObject\MockObject
     */
    private $observerMock;

    /**
     * @var SendSMSOnOrderSuccess
     */
    private $observer;

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

        $this->observer = new SendSMSOnOrderSuccess($this->helperDataMock);
    }

    public function testExecuteShouldNotSendSMSIfNoMobile()
    {
        $this->orderMock->expects($this->any())
            ->method('getShippingAddress')
            ->willReturnSelf();

        $this->orderMock->getShippingAddress()->expects($this->once())
            ->method('__call')
            ->with('getTelephone')
            ->willReturn(null);

        $this->observerMock->expects($this->any())
            ->method('getEvent')
            ->willReturnSelf();

        $this->observerMock->getEvent()->expects($this->once())
            ->method('__call')
            ->with('getOrder')
            ->willReturn($this->orderMock);

        $this->helperDataMock->expects($this->never())
            ->method('orderSuccessSMS');

        $this->observer->execute($this->observerMock);
    }

    public function testExecuteShouldSendSMSIfMobileExists()
    {
        $this->orderMock->expects($this->any())
            ->method('getShippingAddress')
            ->willReturnSelf();

        $this->orderMock->getShippingAddress()->expects($this->once())
            ->method('__call')
            ->with('getTelephone')
            ->willReturn('1234567890');

        $this->orderMock->expects($this->once())
            ->method('getIncrementId')
            ->willReturn(100000001);

        $this->observerMock->expects($this->any())
            ->method('getEvent')
            ->willReturnSelf();

        $this->observerMock->getEvent()->expects($this->once())
            ->method('__call')
            ->with('getOrder')
            ->willReturn($this->orderMock);

        $this->helperDataMock->expects($this->once())
            ->method('orderSuccessSMS')
            ->with(
                "transaction_sms_control/order_confirm_sms/message",
                '1234567890',
                date('d-m-Y', strtotime(date('d-m-Y') . ' +4 days')),
                100000001
            );

        $this->observer->execute($this->observerMock);
    }
}
