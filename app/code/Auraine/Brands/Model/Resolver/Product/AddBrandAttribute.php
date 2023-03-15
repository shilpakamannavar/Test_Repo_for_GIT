<?php

namespace Auraine\Brands\Model\Resolver\Product;

use Magento\Framework\GraphQl\Query\ResolverInterface;

class AddBrandAttribute implements ResolverInterface
{
    /**
     * @var Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @param Magento\Catalog\Model\ProductFactory $productFactory
     */
    public function __construct(
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        $this->productFactory = $productFactory;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        \Magento\Framework\GraphQl\Config\Element\Field $field,
        $context,
        \Magento\Framework\GraphQl\Schema\Type\ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $products = $value['model'];
        $product = $this->productFactory->create()->load($products->getId());
        return $product->getData('brand_name');
    }
}
