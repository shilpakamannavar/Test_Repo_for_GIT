<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\LoyaltyPoint\Model\Resolver;

use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Auraine\LoyaltyPoint\Helper\Data ;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;

/**
 * @inheritdoc
 */
class LoyaltyResolver implements ResolverInterface
{
    
    /**
     * @var \Auraine\LoyaltyPoint\Helper\Data
     */
    protected $helperData;

    /**
     * @var GetCustomer
     */
    private $customerGetter;
 
    /**
     *
     * @param GetCustomer $customerGetter
     * @param Data $helperData
     */
    public function __construct(
        GetCustomer $customerGetter,
        Data $helperData
    ) {
        $this->customerGetter = $customerGetter;
        $this->helperData = $helperData;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $customer = $this->customerGetter->execute($context);
        $customerId = (int)$customer->getId();
        $grandTotal = $this->helperData->getYearOldGrandTotal($customerId);

        return $this->helperData->getSlabValueOrName($grandTotal, true);
    }
}
