<?php
namespace Auraine\LoyaltyPoint\Test\Unit\Model\Resolver;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\LoyaltyPoint\Model\Resolver\LoyaltyResolver
 */
class LoyaltyResolverTest extends TestCase
{
    /**
     * Mock customerGetter
     *
     * @var \Magento\CustomerGraphQl\Model\Customer\GetCustomer|PHPUnit\Framework\MockObject\MockObject
     */
    private $customerGetter;

    /**
     * Mock helperNameById
     *
     * @var \Auraine\LoyaltyPoint\Helper\GetTireNameByid|PHPUnit\Framework\MockObject\MockObject
     */
    private $helperNameById;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\LoyaltyPoint\Model\Resolver\LoyaltyResolver
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->customerGetter = $this->createMock(\Magento\CustomerGraphQl\Model\Customer\GetCustomer::class);
        $this->helperNameById = $this->createMock(\Auraine\LoyaltyPoint\Helper\GetTireNameByid::class);
        $this->testObject = $this->objectManager->getObject(
        \Auraine\LoyaltyPoint\Model\Resolver\LoyaltyResolver::class,
            [
                'customerGetter' => $this->customerGetter,
                'helperNameById' => $this->helperNameById,
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
