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
        /** @var ContextInterface $context */
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
        }
        $customer = $this->getCurrentCustomer->execute($context);
        if (empty($customer->getId())) {
            return null;
        }
        return $customer->getId();
    }
}
