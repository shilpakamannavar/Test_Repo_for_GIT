<?php
declare(strict_types=1);
namespace Auraine\MobileNumber\Model\Resolver;

use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Integration\Api\CustomerTokenServiceInterface;

class GenerateCustomerTokenMobile implements ResolverInterface
{
    /**
     * @var CustomerTokenServiceInterface
     */
    private $customerTokenService;

    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @param CustomerTokenServiceInterface $customerTokenService
     * @param CustomerFactory $customerFactory
     */
    public function __construct(
        CustomerTokenServiceInterface $customerTokenService,
        CustomerFactory $customerFactory,
    ) {
        $this->customerTokenService = $customerTokenService;
        $this->customerFactory = $customerFactory;
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
        if (empty($args['mobile'])) {
            throw new GraphQlInputException(__('Specify the "mobile" value.'));
        }

        if (empty($args['password'])) {
            throw new GraphQlInputException(__('Specify the "password" value.'));
        }

        if (!$this->validateMobile($args['mobile'])) {
            throw new GraphQlAuthenticationException(__('Invalid number.'));
        }

        try {
            $collection = $this->customerFactory->create()->getCollection()
                ->addFieldToFilter("mobilenumber", $args['mobile']);
            if (count($collection) > 0) {
                $email = $collection->getFirstItem()->getEmail();
                $token = $this->customerTokenService->createCustomerAccessToken($email, $args['password']);
                return ['token' => $token];
            } else {
                throw new GraphQlAuthenticationException('Customer not exist!');
            }
        } catch (AuthenticationException $e) {
            throw new GraphQlAuthenticationException(__($e->getMessage()), $e);
        }
    }

    /**
     * @param $mobile
     * @return bool
     */
    public function validateMobile($mobile)
    {
        $pattern = '/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/';
        if(preg_match($pattern, $mobile)) {
            if(strlen($mobile) >= 10 && strlen($mobile) == 12) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
