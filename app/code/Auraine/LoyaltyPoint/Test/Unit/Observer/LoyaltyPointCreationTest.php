<?php

namespace Auraine\LoyaltyPoint\Test\Unit\Observer;

use Auraine\LoyaltyPoint\Helper\Data;
use Auraine\LoyaltyPoint\Observer\LoyaltyPointCreation;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Event;
use Magento\Sales\Model\Order;
use Amasty\Rewards\Api\RewardsProviderInterface;
use Amasty\Rewards\Model\Rule;
use PHPUnit\Framework\TestCase;

class LoyaltyPointCreationTest extends TestCase
{
    /**
     * @var RewardsProviderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $rewardsProviderMock;

    /**
     * @var Rule|\PHPUnit\Framework\MockObject\MockObject
     */
    private $ruleMock;

    /**
     * @var CustomerRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $customerRepositoryMock;

    /**
     * @var Data|\PHPUnit\Framework\MockObject\MockObject
     */
    private $helperDataMock;

    /**
     * @var LoyaltyPointCreation
     */
    private $loyaltyPointCreation;

    protected function setUp(): void
    {
        $this->rewardsProviderMock = $this->createMock(RewardsProviderInterface::class);
        $this->ruleMock = $this->createMock(Rule::class);
        $this->customerRepositoryMock = $this->createMock(CustomerRepositoryInterface::class);
        $this->helperDataMock = $this->createMock(Data::class);

        $this->loyaltyPointCreation = new LoyaltyPointCreation(
            $this->rewardsProviderMock,
            $this->ruleMock,
            $this->customerRepositoryMock,
            $this->helperDataMock
        );
    }

    public function testExecuteWithRegisteredCustomer()
    {
        // Create mock objects for the dependencies
        $rewardsProviderMock = $this->getMockBuilder(\Amasty\Rewards\Api\RewardsProviderInterface::class)
            ->getMock();
        $ruleMock = $this->getMockBuilder(\Amasty\Rewards\Model\Rule::class)
            ->disableOriginalConstructor()
            ->getMock();
        $customerRepositoryMock = $this->getMockBuilder(\Magento\Customer\Api\CustomerRepositoryInterface::class)
            ->getMock();
        $helperDataMock = $this->getMockBuilder(\Auraine\LoyaltyPoint\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Create a mock order object
        $orderMock = $this->getMockBuilder(\Magento\Sales\Model\Order::class)
            ->disableOriginalConstructor()
            ->getMock();
        $orderMock->expects($this->once())
            ->method('getCustomerId')
            ->willReturn(1);
        $orderMock->expects($this->once())
            ->method('getGrandTotal')
            ->willReturn(100);

        // Create a mock event object
        $eventMock = $this->getMockBuilder(\Magento\Framework\Event::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eventMock->expects($this->once())
            ->method('getOrder')
            ->willReturn($orderMock);

        // Create a mock observer object
        $observerMock = $this->getMockBuilder(\Magento\Framework\Event\Observer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $observerMock->expects($this->once())
            ->method('getEvent')
            ->willReturn($eventMock);

        // Create the object under test
        $object = new LoyaltyPointCreation($rewardsProviderMock, $ruleMock, $customerRepositoryMock, $helperDataMock);

        // Call the method under test
        $result = $object->execute($observerMock);

        // Assert the result
        $this->assertEquals($object, $result);
    }
}
