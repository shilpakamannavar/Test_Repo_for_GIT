<?php
namespace Magecomp\MobileloginGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;

class CreateCustomerAccount implements ResolverInterface
{
    /**
     * @var \Magecomp\Mobilelogin\Helper\Data
     */
    protected $_helperData;

    /**
     * CreateCustomerAccount constructor.
     * @param \Magecomp\Mobilelogin\Helper\Data $helperData
     * @param \Magento\CustomerGraphQl\Model\Customer\CreateCustomerAccount $createCustomerAccount
     * @param \Magento\CustomerGraphQl\Model\Customer\ExtractCustomerData $extractCustomerData
     * @param \Magento\Newsletter\Model\Config $newsLetterConfig
     */
    public function __construct(
        \Magecomp\Mobilelogin\Helper\Data $helperData,
        \Magento\CustomerGraphQl\Model\Customer\CreateCustomerAccount $createCustomerAccount,
        \Magento\CustomerGraphQl\Model\Customer\ExtractCustomerData $extractCustomerData
    ) {
        $this->_helperData = $helperData;
        $this->extractCustomerData = $extractCustomerData;
        $this->createCustomerAccount = $createCustomerAccount;
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
        if (empty($args['input']) || !isset($args['input'])) {
            throw new GraphQlInputException(__('Invalid parameter list.'));
        }
        $type = $args['type'];
        if(($type === 'mobile') && !$this->validateMobile($args['mobileNumber'])) {
            throw new GraphQlAuthenticationException(__('Invalid number.'));
        }
        $output = [];
        $output['status'] = false;
        $output['customer'] = [];
        $output['message'] = "";
        try {
            $isVerified = $this->_helperData->checkOTPisVerified(
                [
                    "mobile" => ($type === 'mobile') ? $args['mobileNumber'] : $args['input']['email'],
                    "otp" => $args['otp'],
                ],
                $this->_helperData::REGISTRATION_OTP_TYPE,
                $args['websiteId']
            );

            if (count($isVerified) == 1) {
                if ($type === 'mobile') {
                    $collection = $this->_helperData->checkCustomerExists(
                        $args['mobileNumber'],
                        "mobile",
                        $args['websiteId']
                    );
                } else {
                    $collection = $this->_helperData->checkCustomerExists(
                        $args['input']['email'],
                        "email",
                        $args['websiteId']
                    );
                }
                
                if (count($collection) == 0) {
                    if (isset($args['input']['date_of_birth'])) {
                        $args['input']['dob'] = $args['input']['date_of_birth'];
                    }
                    $args['input']['mobilenumber'] = $args['mobileNumber'];
                    $customer = $this->createCustomerAccount->execute(
                        $args['input'],
                        $context->getExtensionAttributes()->getStore()
                    );
                    $output['customer'] = $this->extractCustomerData->execute($customer);
                    $output['status'] = true;
                    $output['message'] = __("Customer account created.");
                } else {
                    $output['message'] = __("Customer is already exists.");
                }
            } else {
                $output['message'] = __("Mobile no is not verified.");
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
