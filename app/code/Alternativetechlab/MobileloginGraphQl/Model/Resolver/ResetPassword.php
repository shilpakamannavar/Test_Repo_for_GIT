<?php
namespace Alternativetechlab\MobileloginGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;

class ResetPassword implements ResolverInterface
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
        if (!isset($args['mobileNumber']) || !isset($args['otp']) || !isset($args['password']) ||
            empty($args['mobileNumber']) || empty($args['otp']) || empty($args['password'])
        ) {
            throw new GraphQlInputException(__('Invalid parameter list.'));
        }
        if (!$this->validateMobile($args['mobileNumber'])) {
            throw new GraphQlAuthenticationException(__('Invalid number.'));
        }
        $output = [];
        $output['status'] = false;
        $output['message'] = "";
        try {
            $isVerified = $this->helperData->checkOTPisVerified(
                [
                    "mobile"=>$args['mobileNumber'],
                    "otp"=>$args['otp'],
                ],
                $this->helperData::FORGOTPASSWORD_OTP_TYPE,
                $args['websiteId']
            );
            if (count($isVerified) == 1) {
                $output = $this->helperData->resetForgotPassword(
                    ["mobile"=>$args['mobileNumber'],"password"=>$args['password']],
                    $args['websiteId']
                );
            } else {
                $output = ["status"=>false, "message"=>__("Mobile no is not verified.")];
            }
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
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
