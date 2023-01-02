<?php
namespace Auraine\LoyaltyPoint\Observer;

use Magento\Sales\Model\Order;

class LoyaltyPointCreation implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    private $_orderCollectionFactory;

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
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Amasty\Rewards\Api\RewardsProviderInterface $rewardsProvider
     * @param \Amasty\Rewards\Model\Rule $rule
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Auraine\LoyaltyPoint\Helper\Data $helperData
     */
    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Amasty\Rewards\Api\RewardsProviderInterface $rewardsProvider,
        \Amasty\Rewards\Model\Rule $rule,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Auraine\LoyaltyPoint\Helper\Data $helperData
    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
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

                /** Fetching one year old orders of customer from current date */
                $customerOrders = $this->_orderCollectionFactory
                    ->create()
                    ->addFieldToFilter('customer_id', $customerId)
                    ->addFieldToFilter('created_at', ['lteq' => date('Y-m-d')])
                    ->addFieldToFilter('created_at', ['gteq' => date('Y-m-d', strtotime('-1 year'))]);

                /** Calculating the sum of one year orders from current date. */
                $grandTotal = 0;
                foreach($customerOrders as $customerOrder) {
                    $grandTotal += $customerOrder->getGrandTotal();
                }

                $slabs = $this->_helperData->getSlabs();
                $slabValues = $this->_helperData->getValues();

                /** Getting applicable slab value for the customer. */
                if ($grandTotal >= $slabs[1]) {
                    $this->slab = $slabValues[1];
                } else if($grandTotal >= $slabs[2] && $grandTotal < $slabs[1]) {
                    $this->slab = $slabValues[2];
                } else if($grandTotal >= $slabs[3] && $grandTotal < $slabs[2]) {
                    $this->slab = $slabValues[3];
                } else {
                    $this->slab = $slabValues[4];
                }

                /** Calculating loyalty points and updating it with the previous values if any. */
                $amount = $order->getGrandTotal() * ($this->slab / 100);
                $customer = $this->_customerRepository->getById($customerId);
                $this->_rewardsProvider->addPointsByRule($this->rule, $customer->getId(), $customer->getStoreId(), $amount, "Purchase is made bonus for");
            }
        }

        return $this;
    }

    
}
