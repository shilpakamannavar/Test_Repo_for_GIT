<?php
namespace Magecomp\MobileloginGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;

class UpdateMobilenumber implements ResolverInterface
{
    /**
     * @var \Magecomp\Mobilelogin\Helper\Data
     */
    protected $_helperData;

    /**
     * SendOtp constructor.
     * @param \Magecomp\Mobilelogin\Helper\Data $helperData
     */
    public function __construct(
        \Magecomp\Mobilelogin\Helper\Data $helperData
    ) {
        $this->_helperData = $helperData;
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

        if (!isset($args['newmobileNumber']) || !isset($args['customerId']) || !isset($args['oldmobileNumber']) ||  !isset($args['websiteId']) ||
            empty($args['newmobileNumber']) || empty($args['customerId']) || empty($args['oldmobileNumber']) || empty($args['websiteId'])
        ) {
            throw new GraphQlInputException(__('Invalid parameter list.'));
        }
        $output = [];
        $output['status'] = false;
        $output['message'] = "";

        try{

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $customerObj = $objectManager->get('Magento\Customer\Model\ResourceModel\Customer\Collection');
            $collection = $customerObj->addAttributeToSelect('*')
                ->addAttributeToFilter('mobilenumber', $args['newmobileNumber'])
                ->addAttributeToFilter('entity_id', $args['customerId'])
                ->load();
            if (!count($collection) > 0) {


                $output = $this->_helperData->sendUpdateOTPCode($args['newmobileNumber'], $args['websiteId']);

            } else {

                $output =["status"=>false, "message"=>__("Customer does not exist.")];
            }

            return $output;


        }
        catch (\Exception $e) {
            return  $output =[["status"=>false, "message"=>__("Customer does not exist.")]];
            throw new AuthenticationException(__($e->getMessage()));
        }
    }

}
