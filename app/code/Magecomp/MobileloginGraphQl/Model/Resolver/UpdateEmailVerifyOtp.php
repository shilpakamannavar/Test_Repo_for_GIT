<?php
namespace Magecomp\MobileloginGraphQl\Model\Resolver;

use Magecomp\Mobilelogin\Helper\Data;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\Model\Data\Customer as CustomerData;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Customer as CustomerResource;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;

class UpdateEmailVerifyOtp implements ResolverInterface
{
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * @param Data $helperData
     * @param CustomerData $customerData
     * @param Customer $customer
     * @param CustomerResource $customerResource
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Data $helperData,
        CustomerData $customerData,
        Customer $customer,
        CustomerResource $customerResource,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->_helperData = $helperData;
        $this->customerData    = $customerData;
        $this->customer    = $customer;
        $this->customerResource = $customerResource;
        $this->customerRepository = $customerRepository;
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
        if (!isset($args['new_email']) || !isset($args['otp']) ||  !isset($args['old_email']) || !isset($args['websiteId']) ||
            empty($args['new_email']) || empty($args['otp']) || empty($args['old_email']) || empty($args['websiteId'])
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

        try {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $customerObj = $objectManager->create('Magento\Customer\Model\ResourceModel\Customer\Collection');
            $collection = $customerObj->addAttributeToSelect('*')
                ->addAttributeToFilter('email', $args['old_email'])
                ->addAttributeToFilter('entity_id', $customerId)
                ->load();

            if (count($collection) > 0) {
                $output = $this->_helperData->verifyUpdateEmailOTP(
                    ["email"=> $args['new_email'], "verifyotp"=> $args['otp']],
                    $args['websiteId']
                );
                if ($output['status'] == 1) {
                    $customer = $this->customerRepository->getById($customerId);
                    if($customer->getId())
                    {
                        $customer->setWebsiteId($args['websiteId']);
                        $customer->setEmail($args['new_email']);
                    }
                    $this->customerRepository->save($customer)  ;
                    return ["status" => true, "message" => __("Email updated successfully.")];
                }
            } else {
                return ["status"=>false, "message"=>__("Customer does not exist.")];
            }
            return $output;
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        } catch (LocalizedException $e) {
            throw new GraphQlAuthorizationException(__($e->getMessage()), $e);
        }
    }
}
