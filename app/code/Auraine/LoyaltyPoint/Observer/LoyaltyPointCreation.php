<?php
namespace Auraine\LoyaltyPoint\Observer;

use Magento\Sales\Model\Order;

class LoyaltyPointCreation implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Amasty\Rewards\Api\RewardsProviderInterface
     */
    private $_rewardsProvider;

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
    private $_customerRepository;

    /**
     * @var \Auraine\LoyaltyPoint\Helper\Data
     */
    protected $_helperData;

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
        $this->_rewardsProvider = $rewardsProvider;
        $this->rule = $rule;
        $this->_customerRepository = $customerRepository;
        $this->_helperData = $helperData;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        if ($order instanceof \Magento\Framework\Model\AbstractModel) {
            if($order->getState() == Order::STATE_COMPLETE && !$order->getCustomerIsGuest()) {

                $customerId = $order->getCustomerId();
                $grandTotal = $this->_helperData->getYearOldGrandTotal($customerId) - $order->getGrandTotal();

                $this->slab = $this->_helperData->getSlabValueOrName($grandTotal);

                /** Calculating loyalty points and updating it with the previous values if any. */
                $amount = $order->getGrandTotal() * ($this->slab / 100);
                $customer = $this->_customerRepository->getById($customerId);
                $this->_rewardsProvider->addPointsByRule($this->rule, $customer->getId(), $customer->getStoreId(), $amount, "Purchase is made bonus for");
            }
        }

        return $this;
    }

}
