<?php
namespace Auraine\LoyaltyPoint\Test\Unit\Observer;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\LoyaltyPoint\Observer\LoyaltyPointCreation
 */
class LoyaltyPointCreationTest extends TestCase
{
    /**
     * Mock orderCollectionFactoryInstance
     *
     * @var \Magento\Sales\Model\ResourceModel\Order\Collection|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderCollectionFactoryInstance;

    /**
     * Mock orderCollectionFactory
     *
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderCollectionFactory;

    /**
     * Mock rewardsProvider
     *
     * @var \Amasty\Rewards\Api\RewardsProviderInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $rewardsProvider;

    /**
     * Mock rule
     *
     * @var \Amasty\Rewards\Model\Rule|PHPUnit\Framework\MockObject\MockObject
     */
    private $rule;

    /**
     * Mock customerRepository
     *
     * @var \Magento\Customer\Api\CustomerRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $customerRepository;

    /**
     * Mock helperData
     *
     * @var \Auraine\LoyaltyPoint\Helper\Data|PHPUnit\Framework\MockObject\MockObject
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
     * @var \Auraine\LoyaltyPoint\Observer\LoyaltyPointCreation
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->orderCollectionFactoryInstance = $this->createMock(\Magento\Sales\Model\ResourceModel\Order\Collection::class);
        $this->orderCollectionFactory = $this->createMock(\Magento\Sales\Model\ResourceModel\Order\CollectionFactory::class);
        $this->orderCollectionFactory->method('create')->willReturn($this->orderCollectionFactoryInstance);
        $this->rewardsProvider = $this->createMock(\Amasty\Rewards\Api\RewardsProviderInterface::class);
        $this->rule = $this->createMock(\Amasty\Rewards\Model\Rule::class);
        $this->customerRepository = $this->createMock(\Magento\Customer\Api\CustomerRepositoryInterface::class);
        $this->helperData = $this->createMock(\Auraine\LoyaltyPoint\Helper\Data::class);
        $this->testObject = $this->objectManager->getObject(
        \Auraine\LoyaltyPoint\Observer\LoyaltyPointCreation::class,
            [
                'orderCollectionFactory' => $this->orderCollectionFactory,
                'rewardsProvider' => $this->rewardsProvider,
                'rule' => $this->rule,
                'customerRepository' => $this->customerRepository,
                'helperData' => $this->helperData,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestExecute()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestExecute
     */
    public function testExecute(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
