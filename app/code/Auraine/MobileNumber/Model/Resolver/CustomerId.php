<?php

declare(strict_types=1);

namespace Auraine\MobileNumber\Model\Resolver;

use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;

class CustomerId implements ResolverInterface
{
    /**
     * @var Get Current Customer
     */
    private $getCurrentCustomer;
    /**
     * @param GetCustomer $getCurrentCustomer
     */
    public function __construct(GetCustomer $getCurrentCustomer)
    {
        $this->getCurrentCustomer = $getCurrentCustomer;
    }
    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $customer = $this->getCurrentCustomer->execute($context);
        return $customer->getId();
    }
}
