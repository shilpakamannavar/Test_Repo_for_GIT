<?php
namespace Auraine\CouponCodes\Test\Unit\Plugin;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\CouponCodes\Plugin\CheckoutCouponApply
 */
class CheckoutCouponApplyTest extends TestCase
{
    /**
     * Mock helperData
     *
     * @var \Auraine\CouponCodes\Helper\Data|PHPUnit\Framework\MockObject\MockObject
     */
    private $helperData;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\CouponCodes\Plugin\CheckoutCouponApply
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->helperData = $this->createMock(\Auraine\CouponCodes\Helper\Data::class);
        $this->testObject = $this->objectManager->getObject(
        \Auraine\CouponCodes\Plugin\CheckoutCouponApply::class,
            [
                'helperData' => $this->helperData,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestBeforeSet()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestBeforeSet
     */
    public function testBeforeSet(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
