<?php
declare(strict_types=1);

namespace Auraine\LoyaltyPoint\Test\Unit\Model\Resolver;

use Auraine\LoyaltyPoint\Helper\Data;
use Auraine\LoyaltyPoint\Model\Resolver\RewardPointsWillEarn;
use Magento\Customer\Model\Session;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use PHPUnit\Framework\TestCase;

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

    protected function setUp(): void
    {
        $this->helperDataMock = $this->createMock(Data::class);
        $this->customerSessionMock = $this->createMock(Session::class);

        $this->resolver = new RewardPointsWillEarn(
            $this->helperDataMock,
            $this->customerSessionMock
        );
    }

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
















