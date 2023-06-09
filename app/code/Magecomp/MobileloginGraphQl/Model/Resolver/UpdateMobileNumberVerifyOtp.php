<?php
namespace Magecomp\MobileloginGraphQl\Model\Resolver;

use Magecomp\Mobilelogin\Helper\Data;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\Model\Data\Customer as CustomerData;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Customer as CustomerResource;

class UpdateMobileNumberVerifyOtp implements ResolverInterface
{
    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * SendOtp constructor.
     * @param Data $helperData
     */
    public function __construct(
        Data $helperData,
        CustomerData $customerData,
        Customer $customer,
        CustomerResource $customerResource
    ) {
        $this->_helperData = $helperData;
        $this->customerData    = $customerData;
        $this->customer    = $customer;
        $this->customerResource = $customerResource;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array|bool[]|\Magento\Framework\GraphQl\Query\Resolver\Value|mixed
     * @throws GraphQlInputException
     */
    public function resolve(
        Field $field,
              $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($args['newmobileNumber']) || !isset($args['otp']) ||  !isset($args['oldmobileNumber']) || !isset($args['websiteId']) ||
            empty($args['newmobileNumber']) || empty($args['otp']) || empty($args['oldmobileNumber']) || empty($args['websiteId'])
        ) {
            throw new GraphQlInputException(__('Invalid parameter list.'));
        }

        /** @var ContextInterface $context */
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
        }

        $output = [];
        $output['status'] = false;
        $output['message'] = "";
        $customerId = $context->getUserId();
        try{
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $customerObj = $objectManager->create('Magento\Customer\Model\ResourceModel\Customer\Collection');
            $collection = $customerObj->addAttributeToSelect('*')
                ->addAttributeToFilter('mobilenumber', $args['oldmobileNumber'])
                ->addAttributeToFilter('entity_id', $customerId)
                ->load();

            if (count($collection) > 0) {
                $output = $this->_helperData->verifyUpdateMobilenumberOTP(
                    ["mobile"=> $args['newmobileNumber'], "verifyotp"=> $args['otp']],
                    $args['websiteId']
                );

                if ($output['status'] == 1) {
                    $this->customerData->setId($customerId);
                    $this->customerData->setCustomAttribute('mobilenumber', $args['newmobileNumber']);
                    $this->customer->updateData($this->customerData);
                    $this->customerResource->saveAttribute($this->customer, 'mobilenumber');
                    $output = ["status" => true, "message" => __("Mobile number updated successfully.")];
                    return $output;
                }
            } else {
                $output =["status"=>false, "message"=>__("Customer does not exist.")];
                return $output ;
            }
            return $output;
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
    }
}
