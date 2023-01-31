<?php
namespace Auraine\CouponCodes\Plugin;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;

class CheckoutCouponRemove
{

    /**
     * @var \Auraine\CouponCodes\Helper\Data
     */
    private $_helperData;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \Auraine\CouponCodes\Helper\Data $helperData
     */
    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Auraine\CouponCodes\Helper\Data $helperData
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->_helperData = $helperData;
    }

    /**
     * Before plugin validates the incoming request and its header for mobile specific coupons.
     *
     * @param \Magento\Quote\Model\CouponManagement $subject
     * @param int $cartId
     * @return void
     * @throws GraphQlInputException
     */
    public function beforeRemove(\Magento\Quote\Model\CouponManagement $subject, $cartId)
    {

        $quote = $this->quoteRepository->getActive($cartId);
        $couponCode = $quote->getCouponCode();

        if ($couponCode !== null) {

            $collection = $this->_helperData->getCurrentCouponRule()->addFieldToFilter('code', ['eq' => $couponCode]);

            $headerStatus = $this->_helperData->getMobileHeaderStatus();

            if (!empty($collection->getData())) {
                if (!$headerStatus && $collection->getData()[0]['is_mobile_specific'] == 1) {
                    throw new GraphQlInputException(
                        __("Can't remove this coupon, the applied coupon is Mobile specific")
                    );
                }
            }
        }
    }
}
