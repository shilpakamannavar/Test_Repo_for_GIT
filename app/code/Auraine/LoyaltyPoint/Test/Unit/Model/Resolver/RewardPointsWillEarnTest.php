<?php
declare(strict_types=1);

namespace Auraine\LoyaltyPoint\Test\Unit\Model\Resolver;

use Auraine\LoyaltyPoint\Helper\Data;
use Auraine\LoyaltyPoint\Model\Resolver\RewardPointsWillEarn;
use Magento\Customer\Model\Session;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use PHPUnit\Framework\TestCase;
use Magento\Sales\Model\ResourceModel\Order\Collection;
use Magento\Sales\Model\Order;

/**
 * Summary of RewardPointsWillEarnTest
 */
class RewardPointsWillEarnTest extends TestCase
{
    /**
     * @var Data|\PHPUnit\Framework\MockObject\MockObject
     */
    private $helperDataMock;

    /**
     * @var Session|\PHPUnit\Framework\MockObject\MockObject
     */
    private $customerSessionMock;

    /**
     * @var RewardPointsWillEarn
     */
    private $resolver;

    /**
     * @var Collection|\PHPUnit\Framework\MockObject\MockObject
     */
    private $orderCollectionMock;

    /**
     * Summary of setUp
     * @return void
     */
    protected function setUp(): void
    {
        $this->helperDataMock = $this->createMock(Data::class);
        $this->customerSessionMock = $this->createMock(Session::class);
        $this->orderCollectionMock = $this->createMock(Collection::class);

        $this->resolver = new RewardPointsWillEarn(
            $this->helperDataMock,
            $this->customerSessionMock
        );
    }

    /**
     * Summary of testResolve
     * @return void
     */
    public function testResolve()
    {
        $fieldMock = $this->createMock(Field::class);
        $contextMock = $this->getMockBuilder(\Magento\Framework\GraphQl\Query\Resolver\Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $infoMock = $this->createMock(ResolveInfo::class);
        $value = [
            'model' => $this->getMockBuilder(\Magento\Quote\Model\Quote::class)
                ->disableOriginalConstructor()
                ->getMock(),
        ];

        $this->customerSessionMock->expects(self::any())
            ->method('isLoggedIn')
            ->willReturn(true);

        $customerId = 1;

        $this->orderCollectionMock->expects($this->any())
            ->method('addFieldToFilter')
            ->withConsecutive(
                [$this->equalTo('customer_id'), $this->equalTo($customerId)],
                [$this->equalTo('state'), $this->equalTo(Order::STATE_COMPLETE)],
                [$this->equalTo('created_at'), $this->equalTo(['lteq' => date('Y-m-d H:i:s')])],
                [$this->equalTo('created_at'), $this->equalTo(['gteq' => date('Y-m-d H:i:s', strtotime('-1 year'))])]
            )
            ->willReturnSelf();

        $result = $this->resolver->resolve($fieldMock, $contextMock, $infoMock, $value);

        self::assertEquals($result, 0);

    }

    /**
     * Summary of testResolveReturnsNullWhenUserIsNotLoggedIn
     * @return void
     */
    public function testResolveReturnsNullWhenUserIsNotLoggedIn(): void
    {
        $fieldMock = $this->createMock(Field::class);
        $contextMock = $this->getMockBuilder(\Magento\Framework\GraphQl\Query\Resolver\Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $infoMock = $this->createMock(ResolveInfo::class);
        $value = [
            'model' => $this->getMockBuilder(\Magento\Quote\Model\Quote::class)
                ->disableOriginalConstructor()
                ->getMock(),
        ];

        $this->customerSessionMock->expects(self::once())
            ->method('isLoggedIn')
            ->willReturn(false);

        $result = $this->resolver->resolve($fieldMock, $contextMock, $infoMock, $value);

        self::assertNull($result);
    }

    public function testResolveReturnsNullWhenQuoteModelIsMissing(): void
    {
        $fieldMock = $this->createMock(Field::class);
        $contextMock = $this->getMockBuilder(\Magento\Framework\GraphQl\Query\Resolver\Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $infoMock = $this->createMock(ResolveInfo::class);
        $value = [];

        $this->customerSessionMock->expects(self::once())
            ->method('isLoggedIn')
            ->willReturn(true);

        $result = $this->resolver->resolve($fieldMock, $contextMock, $infoMock, $value);

        self::assertNull($result);
    }
}
