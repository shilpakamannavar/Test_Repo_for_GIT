<?php
namespace Alternativetechlab\MobileloginGraphQl\Model\Resolver;

use Alternativetechlab\Mobilelogin\Helper\Data;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;

class ForgotPasswordEmailOtp implements ResolverInterface
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * SendOtp constructor.
     * @param Data $helperData
     */
    public function __construct(
        Data $helperData
    ) {
        $this->helperData = $helperData;
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
        if (empty($args['email']) || !isset($args['email'])) {
            throw new GraphQlInputException(__('Invalid parameter list.'));
        }

        $output = [];
        $output['status'] = false;
        $output['message'] = "";
        try {
            $output = $this->helperData->sendForgotPasswordEmailOTP(
                [
                    "forgotmob"=>$args['email']
                ],
                $args['websiteId']
            );
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $output ;
    }
}
