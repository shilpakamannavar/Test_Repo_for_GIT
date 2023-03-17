<?php

namespace Auraine\FreeShipping\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class FreeShipping implements ResolverInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($value['model'])) {
            throw new GraphQlInputException(__('"model" value must be specified'));
        }

        $cart = $value['model'];
        $subTotal = $cart->getSubtotal();
        $freeShippingAmount = $this->storeManager
            ->getStore()
            ->getWebsite()
            ->getConfig('carriers/freeshipping/free_shipping_subtotal');

        $balanceAmount = max(0, $freeShippingAmount - $subTotal);

        return [
            "status" => $balanceAmount == 0,
            "amount" => $balanceAmount
        ];
    }
}
