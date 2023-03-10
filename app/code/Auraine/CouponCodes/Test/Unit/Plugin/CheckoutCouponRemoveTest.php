<?php
namespace Auraine\CouponCodes\Test\Unit\Plugin;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\CouponCodes\Plugin\CheckoutCouponRemove
 */
class CheckoutCouponRemoveTest extends TestCase
{
    /**
     * Mock quoteRepository
     *
     * @var \Magento\Quote\Api\CartRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $quoteRepository;

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
     * @var \Auraine\CouponCodes\Plugin\CheckoutCouponRemove
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->quoteRepository = $this->createMock(\Magento\Quote\Api\CartRepositoryInterface::class);
        $this->helperData = $this->createMock(\Auraine\CouponCodes\Helper\Data::class);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\CouponCodes\Plugin\CheckoutCouponRemove::class,
            [
                'quoteRepository' => $this->quoteRepository,
                'helperData' => $this->helperData,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestBeforeRemove()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestBeforeRemove
     */
    public function testBeforeRemove(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
