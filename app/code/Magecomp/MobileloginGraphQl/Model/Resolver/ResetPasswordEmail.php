<?php
namespace Magecomp\MobileloginGraphQl\Model\Resolver;

use Magecomp\Mobilelogin\Helper\Data;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Integration\Model\Oauth\TokenFactory;

class ResetPasswordEmail implements ResolverInterface
{
    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * @var TokenFactory
     */
    protected $tokenModelFactory;

    /**
     * VerifyOtp constructor.
     * @param Data $helperData
     * @param TokenFactory $tokenModelFactory
     */
    public function __construct(
        Data $helperData,
        TokenFactory $tokenModelFactory
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
    )
    {
        if (!isset($args['email']) || !isset($args['otp']) || !isset($args['password']) ||
            empty($args['email']) || empty($args['otp']) || empty($args['password'])
        ) {
            throw new GraphQlInputException(__('Invalid parameter list.'));
        }
        $output = [];
        $output['status'] = false;
        $output['message'] = "";
        try {
            $isVerified = $this->_helperData->checkOTPisVerified(
                [
                    "mobile" => $args['email'],
                    "otp" => $args['otp'],
                ],
                $this->_helperData::FORGOTPASSWORD_OTP_TYPE,
                $args['websiteId']
            );
            if (count($isVerified) == 1) {
                $output = $this->_helperData->resetForgotPasswordEmail(
                    ["email" => $args['email'], "password" => $args['password']],
                    $args['websiteId']
                );
            } else {
                $output = ["status" => false, "message" => __("Email is not verified.")];
            }
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $output;
    }
}
