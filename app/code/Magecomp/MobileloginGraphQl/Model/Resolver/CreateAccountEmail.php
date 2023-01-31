<?php

namespace Magecomp\MobileloginGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;

class CreateAccountEmail implements ResolverInterface
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
        Field       $field,
                    $context,
        ResolveInfo $info,
        array       $value = null,
        array       $args = null
    )
    {
        if (empty($args['email']) || !isset($args['email'])) {
            throw new GraphQlInputException(__('Invalid parameter list.'));
        }

        $output = [];
        $output['status'] = false;
        $output['message'] = "";
        try {
            $output = $this->_helperData->sendRegistrationEmailOTP(["mobile" => $args['email']], $args['websiteId']);
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $output;
    }
}
