<?php

declare(strict_types=1);

namespace Auraine\MobileNumber\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Alternativetechlab\Mobilelogin\Helper\Data;

class CheckCustomerExists implements ResolverInterface
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @param Data $helperData
     */
    public function __construct(
        Data $helperData
    ) {
        $this->helperData = $helperData;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $fieldValue = $args['field_value'];
        $type = $args['type'];

        if (empty($fieldValue) || !isset($type)) {
            throw new GraphQlInputException(__('Invalid parameter list.'));
        }

        if ($type === 'mobile' && !$this->validateMobile($fieldValue)) {
            throw new GraphQlAuthenticationException(__('Invalid number.'));
        }

        $collection = $this->helperData->checkCustomerExists(
            $fieldValue,
            $type
        );

        return !empty($collection);
    }

    /**
     * Function for validating mobile number
     *
     * @param String $mobile
     *
     * @return bool
     */
    public function validateMobile($mobile)
    {
        $pattern = '/^(\+?\d{1,4}|\(\d{2,3}\)|\d{2,4})[\s-]?\d{3,4}[\s-]?\d{3,4}$/';
        return preg_match($pattern, $mobile) && strlen($mobile) >= 10 && strlen($mobile) <= 12;
    }
}
