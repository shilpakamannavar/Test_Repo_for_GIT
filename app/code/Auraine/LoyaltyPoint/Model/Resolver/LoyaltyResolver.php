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
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory ;
use Auraine\LoyaltyPoint\Helper\Data ;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Auraine\LoyaltyPoint\Helper\GetTireNameByid ;

/**
 * @inheritdoc
 */
class LoyaltyResolver implements ResolverInterface
{
    
    /**
     * @var \Auraine\LoyaltyPoint\Helper\Data
     */
    protected $_helperData;

    /**
     * @var \Auraine\LoyaltyPoint\Helper\GetTireNameByid
     */
    protected $_helperNameById;

    /**
     * @var GetCustomer
     */
    private $customerGetter;
 
    /**
     *
     * @param CollectionFactory $orderCollectionFactory
     * @param Data $helperData
     * @param GetCustomer $customerGetter
     */
    public function __construct(
        GetCustomer $customerGetter,
        GetTireNameByid $helperNameById,
    ) {
        $this->customerGetter = $customerGetter;
        $this->_helperNameById = $helperNameById;
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
        return $this->_helperNameById->getTireNameById($customerId);
    }
    
}

