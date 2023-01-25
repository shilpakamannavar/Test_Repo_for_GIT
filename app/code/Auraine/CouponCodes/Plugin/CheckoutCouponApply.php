<?php
namespace Auraine\CouponCodes\Plugin;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;

class CheckoutCouponApply
{
 
    /**
     * @var \Auraine\CouponCodes\Helper\Data
     */
    private $_helperData;

    /**
     * @param \Auraine\CouponCodes\Helper\Data $helperData
     */
    public function __construct(
        \Auraine\CouponCodes\Helper\Data $helperData
    ) {
        $this->_helperData = $helperData;
    }

    /**
     * Before plugin validates the incoming request and its header for mobile specific coupons.
     *
     * @param \Magento\Quote\Model\CouponManagement $subject
     * @param int $cartId
     * @param string $couponCode
     * @return void
     * @throws GraphQlInputException
     */
    public function beforeSet(\Magento\Quote\Model\CouponManagement $subject, $cartId, $couponCode)
    {
        $headerStatus = $this->_helperData->getMobileHeaderStatus();

        $collection = $this->_helperData->getCurrentCouponRule()->addFieldToFilter('code', ['eq' => $couponCode]);

        if (!empty($collection->getData())) {
            if (!$headerStatus && $collection->getData()[0]['is_mobile_specific'] == 1) {
                throw new GraphQlInputException(__("Can't apply this coupon, the applied coupon is Mobile specific"));
            }
        }
    }
}
