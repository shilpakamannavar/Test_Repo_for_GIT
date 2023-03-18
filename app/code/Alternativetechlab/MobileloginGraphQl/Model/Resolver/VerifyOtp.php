<?php
namespace Alternativetechlab\MobileloginGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;

class VerifyOtp implements ResolverInterface
{
    /**
     * @var \Alternativetechlab\Mobilelogin\Helper\Data
     */
    protected $helperData;

    /**
     * @var \Magento\Integration\Model\Oauth\TokenFactory
     */
    protected $tokenModelFactory;

    /**
     * VerifyOtp constructor.
     * @param \Alternativetechlab\Mobilelogin\Helper\Data $helperData
     * @param \Magento\Integration\Model\Oauth\TokenFactory $tokenModelFactory
     */
    public function __construct(
        \Alternativetechlab\Mobilelogin\Helper\Data $helperData,
        \Magento\Integration\Model\Oauth\TokenFactory $tokenModelFactory
    ) {
        $this->helperData = $helperData;
        $this->tokenModelFactory = $tokenModelFactory;
    }

    /**
     * @param Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array|\Magento\Framework\GraphQl\Query\Resolver\Value|mixed
     * @throws GraphQlInputException
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($args['mobileNumber']) || !isset($args['otp']) ||
            empty($args['mobileNumber']) || empty($args['otp'])
        ) {
            throw new GraphQlInputException(__('Invalid parameter list.'));
        }
        if (!$this->validateMobile($args['mobileNumber'])) {
            throw new GraphQlAuthenticationException(__('Invalid number.'));
        }
        $output = [];
        $output['status'] = false;
        $output['token'] = "";
        $output['message'] = "";
        try {
            $output = $this->helperData->verifyLoginOTP(
                ["mobile"=>$args['mobileNumber'],"verifyotp"=>$args['otp']],
                $args['websiteId']
            );
            if ($output['status']) {
                $customerCollection = $this->helperData->checkCustomerExists(
                    $args['mobileNumber'],
                    "mobile",
                    $args['websiteId']
                );
                if (count($customerCollection) > 0) {
                    $customer = $customerCollection->getFirstItem();
                    $token = $this->tokenModelFactory->create()->createCustomerToken($customer->getId())->getToken();
                    $output['token'] = $token;
                } else {
                    $output = ["status"=>false, "message"=>__("Customer does not exists.")];
                }
            }
        } catch (NoSuchEntityException $e) {
            throw new GraphQlAuthenticationException(__('Invalid Login or Password.Please try again'), $e);
        }
        return $output ;
    }
    public function validateMobile($mobile)
    {
        $pattern = '/^9\d{11}$/';
        if (preg_match($pattern, $mobile)) {
            return (strlen($mobile) >= 10 && strlen($mobile) == 12) ? true : false;
        } else {
            return false;
        }
    }
}
