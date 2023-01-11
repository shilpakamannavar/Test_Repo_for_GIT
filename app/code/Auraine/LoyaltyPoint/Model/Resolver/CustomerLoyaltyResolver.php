<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\LoyaltyPoint\Model\Resolver;

use Magento\Customer\Model\Customer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory ;
use Auraine\LoyaltyPoint\Helper\Data ;
use Auraine\LoyaltyPoint\Helper\GetTireNameByid ;

/**
 * @inheritdoc
 */
class CustomerLoyaltyResolver implements ResolverInterface
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
     * @var \Auraine\LoyaltyPoint\Helper\GetTireNameByid
     */
    protected $_helperNameById;
    
    /**
     *
     * @param CollectionFactory $orderCollectionFactory
     * @param Data $helperData
     * @param GetTireNameByid $helperNameById
     */
    public function __construct(
        CollectionFactory $orderCollectionFactory,
        Data $helperData,
        GetTireNameByid $helperNameById,
    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_helperData = $helperData;
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
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }
        /** @var Customer $customer */
        $customer = $value['model'];
        $customerId = $customer->getId();
        return $this->_helperNameById->getTireNameById($customerId);
    }
    
}

