<?php
namespace Auraine\LoyaltyPoint\Model\Resolver;

use Auraine\LoyaltyPoint\Helper\Data;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class RewardPointsWillEarn implements ResolverInterface
{
    /**
     * @var \Auraine\LoyaltyPoint\Helper\Data
     */
    private $helperData;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * Constructs rewards points will earn from the current cart items.
     *
     * @param \Auraine\LoyaltyPoint\Helper\Data $helperData
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        Data $helperData,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->helperData = $helperData;
        $this->customerSession = $customerSession;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        if (!$this->customerSession->isLoggedIn() || empty($value['model'])) {
            return null;
        }

        // @codeCoverageIgnoreStart
        $grandTotal = $this->helperData->getYearOldGrandTotal($this->customerSession->getId());
        $slabValue = $this->helperData->getSlabValueOrName($grandTotal);

        return $value['model']->getGrandTotal() * ($slabValue / 100);
        // @codeCoverageIgnoreEnd
    }
}
