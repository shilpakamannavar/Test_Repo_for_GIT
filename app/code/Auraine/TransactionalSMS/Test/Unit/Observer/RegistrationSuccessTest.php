<?php
namespace Auraine\TransactionalSMS\Observer;

class RegistrationSuccessTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Auraine\TransactionalSMS\Helper\Data|\PHPUnit\Framework\MockObject\MockObject $helperDataMock
     */
    private $helperDataMock;

    /**
     * @var \Magento\Framework\Event\Observer|\PHPUnit\Framework\MockObject\MockObject $observerMock
     */
    private $observerMock;

    /**
     * @var \Magento\Customer\Api\Data\CustomerInterface|\PHPUnit\Framework\MockObject\MockObject $customerMock
     */
    private $customerMock;

    /**
     * @var RegistrationSuccess $observer
     */
    private $observer;

    protected function setUp(): void
    {
        $this->helperDataMock = $this->getMockBuilder(\Auraine\TransactionalSMS\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->observerMock = $this->getMockBuilder(\Magento\Framework\Event\Observer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerMock = $this->getMockBuilder(\Magento\Customer\Api\Data\CustomerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->observer = new RegistrationSuccess($this->helperDataMock);
    }

    /**
     * @dataProvider executeDataProvider
     */
    public function testExecute($mobileNumber, $firstName, $lastName)
    {
        $mobileNo = '1234567890';

        $this->customerMock->expects($this->once())
            ->method('getCustomAttribute')
            ->with('mobilenumber')
            ->willReturn($mobileNumber);
        
        $mobileNumber->expects($this->once())
            ->method('getValue')
            ->willReturn($mobileNo);

        $this->customerMock->expects($this->once())
            ->method('getFirstname')
            ->willReturn($firstName);

        $this->customerMock->expects($this->once())
            ->method('getLastname')
            ->willReturn($lastName);

        $this->observerMock->expects($this->once())
            ->method('getEvent')
            ->willReturnSelf();

        $this->observerMock->expects($this->once())

            ->method('__call')
            ->with('getCustomer')
            ->willReturn($this->customerMock);

        $this->helperDataMock->expects($this->any())
            ->method('customerRegisterSuccessSMS')
            ->with(
                "transaction_sms_control/registration_sms/message",
                $mobileNo,
                $firstName . ' ' . $lastName
            );

        $this->observer->execute($this->observerMock);
    }

    public function executeDataProvider()
    {
        return [
            [
                $this->createMock(\Magento\Framework\Api\AttributeInterface::class),
                'John',
                'Doe',
            ],
            [
                $this->createMock(\Magento\Framework\Api\AttributeInterface::class),
                'Foo',
                'Bar',
            ],
        ];
    }
}
