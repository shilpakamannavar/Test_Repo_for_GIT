<?php
namespace Auraine\CouponCodes\Model\DataProvider;

class Collection
{
    /**
     * @var \Auraine\CouponCodes\Helper\Data
     */
    protected $helperData;

    /**
     * @var \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory
     */
    protected $utility;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $serializer;

    /**
     * Filter Sales rules with condition and return valid coupons only.
     *
     * @param \Auraine\CouponCodes\Helper\Data $helperData
     * @param \Magento\SalesRule\Model\Utility $utility
     * @param \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     */
    public function __construct(
        \Auraine\CouponCodes\Helper\Data $helperData,
        \Magento\SalesRule\Model\Utility $utility,
        \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $collectionFactory,
        \Magento\Framework\Serialize\SerializerInterface $serializer
    ) {
        $this->helperData = $helperData;
        $this->utility = $utility;
        $this->collectionFactory = $collectionFactory;
        $this->serializer = $serializer;
    }

    /**
     * Get rules collection for current object state.
     *
     * @param boolean $isMobile default = false
     * @return \Magento\SalesRule\Model\ResourceModel\Rule\Collection
     */
    private function getRulesCollection($isMobile = false)
    {
        $collection = $this->helperData->getCurrentCouponRule();

        return !$isMobile ? $collection->addFieldToFilter('is_mobile_specific', ['neq' => '1']) : $collection;
    }

    /**
     * Filter Sales rules with condition and return valid coupons only.
     *
     * @param boolean $isMobile default = false
     * @return string[] couponCodeArray
     */
    public function getValidCouponList($isMobile = false)
    {
        $rules = $this->getRulesCollection($isMobile);
        $ruleArray = [];

        foreach ($rules as $rule) {
            $ruleArray [] = $this->generateResponse($rule);
        }

        return $ruleArray;
    }

    /**
     * Response of Filter Sales rules with condition and return valid coupons only.
     *
     * @param \Magento\SalesRule\Model\ResourceModel\Rule\Collection $rule
     * @return string[] couponCodeArray
     */
    private function generateResponse($rule)
    {
        return [
            'name' => $rule->getName(),
            'description' => $rule->getDescription(),
            'coupon' => $rule->getCode(),
            'is_mobile_specific' => (bool) $rule->getIsMobileSpecific(),
            'is_applied_on_full_price' => (bool) $rule->getIsAppliedOnFullPrice(),
            'value' => $rule->getValue(),
            'from_date' => $rule->getData('from_date'),
            'to_date' => $rule->getData('to_date'),
            'is_active' => $rule->getIsActive(),
            'action' => $rule->getSimpleAction(),
            'discount_amount' => $rule->getDiscountAmount(),
            'uses_per_customer' => $rule->getUsesPerCustomer(),
            'uses_per_coupon' => $rule->getUsesPerCoupon(),
            'times_used' => $rule->getTimesUsed(),
            'product_ids' => $rule->getProductIds(),
        ];
    }
}
