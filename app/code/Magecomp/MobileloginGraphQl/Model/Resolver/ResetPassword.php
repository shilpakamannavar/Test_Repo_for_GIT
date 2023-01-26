<?php
namespace Magecomp\MobileloginGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;

class ResetPassword implements ResolverInterface
{
    /**
     * @var \Magecomp\Mobilelogin\Helper\Data
     */
    protected $_helperData;

    /**
     * @var \Magento\Integration\Model\Oauth\TokenFactory
     */
    protected $tokenModelFactory;

    /**
     * VerifyOtp constructor.
     * @param \Magecomp\Mobilelogin\Helper\Data $helperData
     * @param \Magento\Integration\Model\Oauth\TokenFactory $tokenModelFactory
     */
    public function __construct(
        \Magecomp\Mobilelogin\Helper\Data $helperData,
        \Magento\Integration\Model\Oauth\TokenFactory $tokenModelFactory
    ) {
        $this->_helperData = $helperData;
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
        if(!$this->validateMobile($args['mobileNumber'])) {
            throw new GraphQlAuthenticationException(__('Invalid number.'));
        }
        $output = [];
        $output['status'] = false;
        $output['message'] = "";
        try {
            $isVerified = $this->_helperData->checkOTPisVerified(
                [
                    "mobile"=>$args['mobileNumber'],
                    "otp"=>$args['otp'],
                ],
                $this->_helperData::FORGOTPASSWORD_OTP_TYPE,
                $args['websiteId']
            );
            if (count($isVerified) == 1) {
                $output = $this->_helperData->resetForgotPassword(
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
