<?php
namespace Auraine\ExtendedCustomerGraphQLException\Plugin;

use Magento\Integration\Api\CustomerTokenServiceInterface;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class GenerateCustomerTokenPlugin
{
   /**
    * @var CustomerTokenServiceInterface
    */
    private $customerTokenService;

    /**
     * @param CustomerTokenServiceInterface $customerTokenService
     */
    public function __construct(
        CustomerTokenServiceInterface $customerTokenService
    ) {
        $this->customerTokenService = $customerTokenService;
    }

    /**
     * Overwrite the customer login error message
     *
     * @param \Magento\CustomerGraphQl\Model\Resolver\GenerateCustomerToken $subject
     * @param callable $callable
     * @param Field $field
     * @param mixed $context
     * @param ResolveInfo $info
     * @param array $value
     * @param array $args
     *
     * @return string
     */
    public function aroundResolve(
        \Magento\CustomerGraphQl\Model\Resolver\GenerateCustomerToken $subject,
        callable $callable,
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {

        if (empty($args['email'])) {
            throw new GraphQlInputException(__('Specify the "email" value.'));
        }

        if (empty($args['password'])) {
            throw new GraphQlInputException(__('Specify the "password" value.'));
        }

        try {
            $token = $this->customerTokenService->createCustomerAccessToken($args['email'], $args['password']);
            return ['token' => $token];
        } catch (AuthenticationException $e) {
            throw new GraphQlAuthenticationException(__('Invalid Login or Password.Please try again'), $e);
        }
    }
}
