<?php

declare(strict_types=1);

namespace Auraine\MobileNumber\Model\Resolver;

use Magento\Customer\Model\Session;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;

class CustomerId implements ResolverInterface
{
    /**
     * @var customerSession
     */
    private $customerSession;
    /**
     * @param Session $customerSession
     */
    public function __construct(Session $customerSession)
    {
        $this->customerSession = $customerSession;
    }
    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $customerId = $this->customerSession->getCustomerId();
        return (int) $customerId;
    }
}
