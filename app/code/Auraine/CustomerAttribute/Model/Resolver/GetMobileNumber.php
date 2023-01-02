<?php
declare(strict_types=1);

namespace Auraine\CustomerAttribute\Model\Resolver;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;

class GetMobileNumber implements ResolverInterface
{
    private CustomerRepositoryInterface $customerRepositoryInterface;

    /**
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     */
    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
    ) {
        $this->customerRepositoryInterface = $customerRepositoryInterface;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        
        if (!isset($value['email']) || '' == trim($value['email'])) {
            throw new GraphQlInputException(__('Specify the "email" value.'));
        }

        $customer = $this->customerRepositoryInterface->get($value['email']);

        return $customer->getExtensionAttributes()->getMobileNumber();
    }
}
