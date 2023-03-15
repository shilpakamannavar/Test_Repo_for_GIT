<?php
namespace Auraine\LoyaltyPoint\Helper;

use Magento\Sales\Model\Order;

class Data
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    private $orderCollectionFactory;

    /**
     * Constructs helper Service provider to fetch Store config values.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     *
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->orderCollectionFactory = $orderCollectionFactory;
    }

    /**
     * Prepare array with tire slabs
     *
     * @return array
     */
    private function getSlabs()
    {
        return [
            1 => $this->getStoreConfigValue('auraine/tire_one/tire_one_amount'),
            2 => $this->getStoreConfigValue('auraine/tire_two/tire_two_amount'),
            3 => $this->getStoreConfigValue('auraine/tire_three/tire_three_amount'),
            4 => $this->getStoreConfigValue('auraine/tire_four/tire_four_amount'),
        ];
    }

    /**
     * Prepare array with tire slabs values
     *
     * @return array
     */
    private function getValues()
    {
        return [
            1 => $this->getStoreConfigValue('auraine/tire_one/tire_one_value'),
            2 => $this->getStoreConfigValue('auraine/tire_two/tire_two_value'),
            3 => $this->getStoreConfigValue('auraine/tire_three/tire_three_value'),
            4 => $this->getStoreConfigValue('auraine/tire_four/tire_four_value'),
        ];
    }

    /**
     * Fetch the store config data
     *
     * @param string $path
     * @return int
     */
    private function getStoreConfigValue($path)
    {
        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * Prepare array with tire Names
     *
     * @return array
     */
    private function getNames()
    {
        return [
            1 => $this->getStoreConfigValue('auraine/tire_one/tire_one_name'),
            2 => $this->getStoreConfigValue('auraine/tire_two/tire_two_name'),
            3 => $this->getStoreConfigValue('auraine/tire_three/tire_three_name'),
            4 => $this->getStoreConfigValue('auraine/tire_four/tire_four_name'),
        ];
    }

    /**
     * Calculate all the completed orders grand total
     *
     * @param int $customerId
     * @return float
     */
    public function getYearOldGrandTotal($customerId)
    {
        /** Fetching one year old orders of customer from current date */
        $customerOrders = $this->orderCollectionFactory
            ->create()
            ->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('state', Order::STATE_COMPLETE)
            ->addFieldToFilter('created_at', ['lteq' => date('Y-m-d H:i:s')])
            ->addFieldToFilter('created_at', ['gteq' => date('Y-m-d H:i:s', strtotime('-1 year'))]);

        /** Calculating the sum of one year orders from current date. */
        $grandTotal = 0;
        foreach ($customerOrders as $customerOrder) {
            $grandTotal += $customerOrder->getGrandTotal();
        }

        return $grandTotal;
    }

    /**
     * Returns slab value/name
     *
     * @param float $grandTotal
     * @param boolean $nameFlag
     * @return mixed
     */
    public function getSlabValueOrName($grandTotal, $nameFlag = false)
    {
        $slabs = $this->getSlabs();
        $slabValues = $this->getValues();
        $slabNames = $this->getNames();

        $name = null;
        $value = null;

        /** Getting applicable slab value and names for the customer. */
        if ($grandTotal >= $slabs[1]) {
            $value = $slabValues[1];
            $name = $slabNames[1];
        } elseif ($grandTotal >= $slabs[2] && $grandTotal < $slabs[1]) {
            $value = $slabValues[2];
            $name = $slabNames[2];
        } elseif ($grandTotal >= $slabs[3] && $grandTotal < $slabs[2]) {
            $value = $slabValues[3];
            $name = $slabNames[3];
        } else {
            $value = $slabValues[4];
            $name = $slabNames[4];
        }

        return $nameFlag ? $name : $value;
    }
}
