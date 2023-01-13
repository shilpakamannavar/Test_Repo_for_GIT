<?php
namespace Auraine\CouponCodes\Test\Unit\Model\Resolver;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\CouponCodes\Model\Resolver\Coupons
 */
class CouponsTest extends TestCase
{
    /**
     * Mock quoteIdMaskFactory
     *
     * @var \Magento\Quote\Model\QuoteIdMask|PHPUnit\Framework\MockObject\MockObject
     */
    private $quoteIdMaskFactory;

    /**
     * Mock ruleCollection
     *
     * @var \Auraine\CouponCodes\Model\DataProvider\Collection|PHPUnit\Framework\MockObject\MockObject
     */
    private $ruleCollection;

    /**
     * Mock maskedQuoteIdToQuoteId
     *
     * @var \Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $maskedQuoteIdToQuoteId;

    /**
     * Mock objectManger
     *
     * @var \Magento\Framework\ObjectManagerInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $objectManger;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\CouponCodes\Model\Resolver\Coupons
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->quoteIdMaskFactory = $this->createMock(\Magento\Quote\Model\QuoteIdMask::class);
        $this->ruleCollection = $this->createMock(\Auraine\CouponCodes\Model\DataProvider\Collection::class);
        $this->maskedQuoteIdToQuoteId = $this->createMock(\Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface::class);
        $this->objectManger = $this->createMock(\Magento\Framework\ObjectManagerInterface::class);
        $this->testObject = $this->objectManager->getObject(
        \Auraine\CouponCodes\Model\Resolver\Coupons::class,
            [
                'quoteIdMaskFactory' => $this->quoteIdMaskFactory,
                'ruleCollection' => $this->ruleCollection,
                'maskedQuoteIdToQuoteId' => $this->maskedQuoteIdToQuoteId,
                'objectManger' => $this->objectManger,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestResolve()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestResolve
     */
    public function testResolve(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
