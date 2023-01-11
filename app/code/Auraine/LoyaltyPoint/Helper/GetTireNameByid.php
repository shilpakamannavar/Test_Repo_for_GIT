<?php
namespace Auraine\LoyaltyPoint\Helper;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory ;
use Auraine\LoyaltyPoint\Helper\Data ;

class GetTireNameByid
{
     /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    private $_orderCollectionFactory;

    /**
     * @var \Auraine\LoyaltyPoint\Helper\Data
     */
    protected $_helperData;

     /**
     * @var string
     */
    private $slab_name;

    /**
     *
     * @param CollectionFactory $orderCollectionFactory
     * @param Data $helperData
     */
    public function __construct(
        CollectionFactory $orderCollectionFactory,
        Data $helperData,
    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_helperData = $helperData;
    }

    /**
     *
     * @param Int $id
     * @return String 
     */
    public function getTireNameById($id)
    {
           /** Fetching one year old orders of customer from current date */
        $customerOrders = $this->_orderCollectionFactory
                                ->create()
                                ->addFieldToFilter('customer_id', $id)
                                ->addFieldToFilter('created_at', ['lteq' => date('Y-m-d')])
                                ->addFieldToFilter('created_at', ['gteq' => date('Y-m-d', strtotime('-1 year'))]);

        /** Calculating the sum of one year orders from current date. */
        $grandTotal = 0;
        foreach($customerOrders as $customerOrder) {
            $grandTotal += $customerOrder->getGrandTotal();
        }

        $slabs = $this->_helperData->getSlabs();
        $slabName = $this->_helperData->getNames();

        /** Getting applicable slab value for the customer. */
        if ($grandTotal >= $slabs[1]) {
            $this->slab_name = $slabName[1];
        } else if($grandTotal >= $slabs[2] && $grandTotal < $slabs[1]) {
            $this->slab_name = $slabName[2];
        } else if($grandTotal >= $slabs[3] && $grandTotal < $slabs[2]) {
            $this->slab_name = $slabName[3];
        } else {
            $this->slab_name = $slabName[4];
        }

        return $this->slab_name;
    }
}
