<?php
namespace Auraine\Brands\Model\Resolver\Product;


use Magento\Framework\GraphQl\Query\ResolverInterface;
use Auraine\Brands\Model\Product\Attribute\Source\BrandAttrProd;

class AddBrandAttributeLabel implements ResolverInterface
{
    /**
     * @var Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * Brand varible
     *
     * @var Auraine\Brands\Model\Product\Attribute\Source\BrandAttrProd
     */
    protected $brand;

    /**
     * @param Magento\Catalog\Model\ProductFactory $productFactory
     */
    public function __construct(
        \Magento\Catalog\Model\ProductFactory $productFactory,
        BrandAttrProd $brand
    ) {
        $this->productFactory = $productFactory;
        $this->brand = $brand;
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
        $product = $value['model'];
        $productData = $this->productFactory->create()->load($product->getId());
        $return = $this->brand->getAllBrandNameById((int)$productData->getData('brand_name'));
        return $return[0]['title'];
    }
}
