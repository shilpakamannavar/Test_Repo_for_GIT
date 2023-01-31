<?php
namespace Magecomp\MobileloginGraphQl\Model\Resolver;

use Magecomp\Mobilelogin\Helper\Data;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;

class UpdateMobilenumber implements ResolverInterface
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
        Data $helperData
    ) {
        $this->_helperData = $helperData;
    }

    /**
     * @param Field $field
     * @param ContextInterface $context
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
        if (!isset($args['newmobileNumber']) || !isset($args['oldmobileNumber']) ||  !isset($args['websiteId']) ||
            empty($args['newmobileNumber']) || empty($args['oldmobileNumber']) || empty($args['websiteId'])
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
            $customerObj = $objectManager->get('Magento\Customer\Model\ResourceModel\Customer\Collection');
            $collection = $customerObj->addAttributeToSelect('*')
                ->addAttributeToFilter('mobilenumber', $args['newmobileNumber'])
                ->addAttributeToFilter('entity_id', $customerId)
                ->load();
            if (!count($collection) > 0) {
                $output = $this->_helperData->sendUpdateOTPCode($args['newmobileNumber'], $args['websiteId']);
            } else {
                $output =["status"=>false, "message"=>__("Customer already exist.")];
            }
            return $output;
        }
        catch (\Exception $e) {
            return  $output =[["status"=>false, "message"=>__("Customer does not exist.")]];
            throw new AuthenticationException(__($e->getMessage()));
        }
    }
}
