<?php
namespace Auraine\LoyaltyPoint\Observer;

use Magento\Sales\Model\Order;

class LoyaltyPointCreation implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Amasty\Rewards\Api\RewardsProviderInterface
     */
    private $rewardsProvider;

    /**
     * @var int
     */
    private $slab = 0;

    /**
     * @var \Amasty\Rewards\Model\Rule
     */
    private $rule;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Auraine\LoyaltyPoint\Helper\Data
     */
    protected $helperData;

    /**
     * Constructs Loyalty point creation service object.
     *
     * @param \Amasty\Rewards\Api\RewardsProviderInterface $rewardsProvider
     * @param \Amasty\Rewards\Model\Rule $rule
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Auraine\LoyaltyPoint\Helper\Data $helperData
     */
    public function __construct(
        \Amasty\Rewards\Api\RewardsProviderInterface $rewardsProvider,
        \Amasty\Rewards\Model\Rule $rule,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Auraine\LoyaltyPoint\Helper\Data $helperData
    ) {
        $this->rewardsProvider = $rewardsProvider;
        $this->rule = $rule;
        $this->customerRepository = $customerRepository;
        $this->helperData = $helperData;
    }

    /**
     * @inheritdoc
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        if (
            $order instanceof \Magento\Framework\Model\AbstractModel &&
            ($order->getState() == Order::STATE_COMPLETE && !$order->getCustomerIsGuest())
        ) {
            $customerId = $order->getCustomerId();
            $grandTotal = $this->helperData->getYearOldGrandTotal($customerId) - $order->getGrandTotal();

            $this->slab = $this->helperData->getSlabValueOrName($grandTotal);

            /** Calculating loyalty points and updating it with the previous values if any. */
            $amount = $order->getGrandTotal() * ($this->slab / 100);
            $customer = $this->customerRepository->getById($customerId);
            $this->rewardsProvider->addPointsByRule(
                $this->rule,
                $customer->getId(),
                $customer->getStoreId(),
                $amount,
                "Purchase is made bonus for"
            );
        }

        return $this;
    }
}
