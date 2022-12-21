<?php
namespace Auraine\CouponCodes\Model\DataProvider;

class Collection
{
    /**
     * @var \Auraine\CouponCodes\Helper\Data
     */
    protected $_helperData;

    /**
     * @var \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory
     */
    protected $_utility;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $serializer;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        \Auraine\CouponCodes\Helper\Data $helperData,
        \Magento\SalesRule\Model\Utility $utility,
        \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $collectionFactory,
        \Magento\Framework\Serialize\SerializerInterface $serializer
    ) {
        $this->_helperData = $helperData;
        $this->_utility = $utility;
        $this->_collectionFactory = $collectionFactory;
        $this->serializer = $serializer;
    }

    /**
     * Get rules collection for current object state.
     *
     * @return \Magento\SalesRule\Model\ResourceModel\Rule\Collection
     */
    private function getRulesCollection()
    {
        $websiteId = $this->_helperData->getWebsiteId();
        $customerGroupId = $this->_helperData->getCustomerGroupId();

        return $this->_collectionFactory->create()
                ->addWebsiteGroupDateFilter($websiteId, $customerGroupId)
                ->addAllowedSalesRulesFilter()
                ->addFieldToFilter('coupon_type', ['neq' => '1'])
                ->addFieldToFilter('is_visible_in_list', ['eq' => '1']);
    }

    /**
     * Filter Sales rules with condition and return valid coupons only.
     *
     * @param array $quote
     * @return string[] couponCodeArray
     */
    public function getValidCouponList($quote)
    {
        $rules = $this->getRulesCollection();
        $ruleArray = [];

        $items = $quote->getAllVisibleItems();

        foreach ($rules as $rule) {

            $validAction = false;

            foreach ($items as $item) {
                if ($validAction = $rule->getActions()->validate($item)) {
                    break;
                }
            }

            if ($validAction) {
                $ruleArray [] = $this->generateResponse($rule);
            }
        }

        return $ruleArray;
    }

    /**
     * Response of Filter Sales rules with condition and return valid coupons only.
     *
     * @return string[] couponCodeArray
     */
    private function generateResponse($rule)
    {
        return [
            'name' => $rule->getName(),
            'description' => $rule->getDescription(),
            'coupon' => $rule->getCode(),
            'value' => $rule->getValue(),
            'from_date' => $rule->getFromDate(),
            'to_date' => $rule->getToDate(),
            'is_active' => $rule->getIsActive(),
            'action' => $rule->getSimpleAction(),
            'discount_amount' => $rule->getDiscountAmount(),
            'uses_per_customer' => $rule->getUsesPerCustomer(),
            'uses_per_coupon' => $rule->getUsesPerCoupon(),
            'times_used' => $rule->getTimesUsed(),
            'product_ids' => $rule->getProductIds(),
            'rule' => $this->couponRules($rule)
        ];

    }

    /**
     * Filter Sales rules conditions collection for individual coupons.
     *
     * @return string[] conditionsArray
     */
    private function couponRules($rule)
    {
        $ruleConditions = [];

        $ruleDataArray = $this->serializer->unserialize($rule->getData('conditions_serialized'));
            
        if (isset($ruleDataArray['conditions'])) {
            foreach ($ruleDataArray['conditions'] as $condition) {
                
                if (isset($condition['conditions'])) {
                    
                    foreach ($condition['conditions'] as $productCondition) {
                        if (isset($productCondition['value'])) {
                            if (isset($productCondition['conditions'])) {
                                foreach($productCondition['conditions'] as $prodRule) {
                                    $ruleConditions['rules'][] = $this->generateRule($prodRule);
                                }
                            } else {
                                $ruleConditions['rules'][] = $this->generateRule($productCondition);
                            }
                        }
                    }
                } 
                else {
                    $ruleConditions['rules'][] = $this->generateRule($condition);
                }
            }
        }
        
        return isset($ruleConditions['rules']) ? $ruleConditions['rules'] : $ruleConditions;
    }

    /**
     * Coupon rules.
     *
     * @param array $rule 
     * @param boolean $isProduct
     * @return string[] couponCodeArray
     */
    private function generateRule($rule, $isProduct = false): array
    {
        return [
            'attribute' => $rule['attribute'],
            'operator' => $rule['operator'],
            'value' => $rule['value'] 

            // Use the below line if any requirement comes for SKU array.
            // $isProduct ? explode(',', $rule['value']) : $rule['value'],
        ];
    }

}
