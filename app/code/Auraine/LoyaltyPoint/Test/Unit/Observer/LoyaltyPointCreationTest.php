<?php

namespace Auraine\LoyaltyPoint\Test\Unit\Observer;

use Auraine\LoyaltyPoint\Observer\LoyaltyPointCreation;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order;
use PHPUnit\Framework\TestCase;
use Amasty\Rewards\Api\RewardsProviderInterface;
use Amasty\Rewards\Model\Rule;
use Auraine\LoyaltyPoint\Helper\Data;

class LoyaltyPointCreationTest extends TestCase
{
    /**
     * @var RewardsProviderInterface
     */
    private $rewardsProviderMock;

    /**
     * @var Rule
     */
    private $ruleMock;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepositoryMock;

    /**
     * @var Data
     */
    private $helperDataMock;

    /**
     * setUp
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->rewardsProviderMock = $this->getMockBuilder(RewardsProviderInterface::class)
            ->getMock();
        $this->ruleMock = $this->getMockBuilder(Rule::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->customerRepositoryMock = $this->getMockBuilder(CustomerRepositoryInterface::class)
            ->getMock();
        $this->helperDataMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @dataProvider orderDataProvider
     *
     * @return void
     */
    public function testExecute(Order $order, $expectedAddPointsCall)
    {
        $observerMock = $this->getMockBuilder(Observer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $observerMock->expects($this->once())
            ->method('getEvent')
            ->willReturn($observerMock);

        $observerMock->method('getData')
            ->with('order')
            ->willReturn($order);

        $observerMock->expects($this->once())
            ->method('__call')
            ->with('getOrder')
            ->willReturn($order);

        $customerMock = $this->getMockBuilder(\Magento\Customer\Api\Data\CustomerInterface::class)
            ->getMock();

        $this->customerRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($order->getCustomerId())
            ->willReturn($customerMock);

        $grandTotal = 100;
        $this->helperDataMock->expects($this->once())
            ->method('getYearOldGrandTotal')
            ->with($order->getCustomerId())
            ->willReturn($grandTotal);

        $slab = 10;
        $this->helperDataMock->expects($this->once())
            ->method('getSlabValueOrName')
            ->with($grandTotal - $order->getGrandTotal())
            ->willReturn($slab);

        $points = $order->getGrandTotal() * ($slab / 100);

        $this->rewardsProviderMock->expects($expectedAddPointsCall)
            ->method('addPointsByRule')
            ->with(
                $this->ruleMock,
                $customerMock->getId(),
                $customerMock->getStoreId(),
                $points,
                "Purchase is made bonus for"
            );

        $observer = new LoyaltyPointCreation(
            $this->rewardsProviderMock,
            $this->ruleMock,
            $this->customerRepositoryMock,
            $this->helperDataMock
        );

        $this->assertSame($observer, $observer->execute($observerMock));
    }

    public function orderDataProvider()
    {
        return [
            [
                $this->createOrder(Order::STATE_COMPLETE, false),
                $this->once(),
            ],
        ];
    }

    private function createOrder($state, $isGuest)
    {
        $orderMock = $this->getMockBuilder(Order::class)
        ->disableOriginalConstructor()
        ->getMock();
        $orderMock->method('getState')
        ->willReturn($state);
        $orderMock->method('getCustomerId')
        ->willReturn($isGuest ? null : 1);
        $orderMock->method('getGrandTotal')
        ->willReturn(100);

        return $orderMock;
    }
}
