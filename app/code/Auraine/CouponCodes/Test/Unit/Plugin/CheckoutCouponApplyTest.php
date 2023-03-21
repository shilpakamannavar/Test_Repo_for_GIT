<?php
namespace Auraine\CouponCodes\Test\Unit\Plugin;

namespace Auraine\CouponCodes\Test\Unit\Plugin;

use Auraine\CouponCodes\Helper\Data;
use Auraine\CouponCodes\Plugin\CheckoutCouponApply;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Quote\Model\CouponManagement;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class CheckoutCouponApplyTest extends TestCase
{
    /**
     * @var CheckoutCouponApply
     */
    private $plugin;

    /**
     * @var Data|MockObject
     */
    private $helperMock;

    /**
     * @var CouponManagement|MockObject
     */
    private $subjectMock;

    protected function setUp(): void
    {
        $this->helperMock = $this->createMock(Data::class);
        $this->subjectMock = $this->createMock(CouponManagement::class);

        $this->plugin = new CheckoutCouponApply($this->helperMock);
    }

    /**
     * @dataProvider couponCodeProvider
     */
    public function testBeforeSetWithMobileCouponCode(string $couponCode, bool $headerStatus, bool $isMobileSpecific, bool $expectException)
    {
        $cartId = 1;
        $this->helperMock->expects($this->once())->method('getMobileHeaderStatus')->willReturn($headerStatus);

        $collectionMock = $this->getMockBuilder(\Magento\SalesRule\Model\ResourceModel\Rule\Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionMock->method('addFieldToFilter')->willReturn($collectionMock);
        $collectionMock->method('getData')->willReturn([['code' => $couponCode, 'is_mobile_specific' => $isMobileSpecific]]);

        $this->helperMock->expects($this->once())->method('getCurrentCouponRule')->willReturn($collectionMock);

        if ($expectException) {
            $this->expectException(GraphQlInputException::class);
            $this->expectExceptionMessage("Can't apply this coupon, the applied coupon is Mobile specific");
        } else {
            $this->subjectMock->expects($this->once())->method('set')->with($cartId, $couponCode);
        }

        $this->plugin->beforeSet($this->subjectMock, $cartId, $couponCode);
    }

    public function couponCodeProvider(): array
    {
        return [
            // ['coupon_code_1', true, false, false], // mobile coupon code with mobile header enabled
            // ['coupon_code_2', false, false, false], // non-mobile coupon code with mobile header disabled
            ['coupon_code_3', false, true, true], // mobile coupon code with mobile header disabled
            // ['coupon_code_4', true, true, false], // mobile coupon code with mobile header enabled
        ];
    }
}