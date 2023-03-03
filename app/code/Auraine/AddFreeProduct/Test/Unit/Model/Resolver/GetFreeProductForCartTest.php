<?php
namespace Auraine\AddFreeProduct\Test\Unit\Model\Resolver;

class GetFreeProductForCartTest extends \PHPUnit\Framework\TestCase
{
    public function testResolveReturnsEmptyArrayWhenNoFreeItems()
    {
        $promoItemRegistry = $this->createMock(\Amasty\Promo\Model\ItemRegistry\PromoItemRegistry::class);
        $promoItemRegistry->expects($this->once())
            ->method('getAllItems')
            ->willReturn([]);

        $productRepository = $this->createMock(\Magento\Catalog\Api\ProductRepositoryInterface::class);

        $storeManager = $this->createMock(\Magento\Store\Model\StoreManagerInterface::class);

        $resolver = new \Auraine\AddFreeProduct\Model\Resolver\GetFreeProductForCart(
            $promoItemRegistry,
            $productRepository,
            $storeManager
        );

        $field = $this->createMock(\Magento\Framework\GraphQl\Config\Element\Field::class);
        $context = [];
        $info = $this->createMock(\Magento\Framework\GraphQl\Schema\Type\ResolveInfo::class);
        $value = null;
        $args = null;

        $result = $resolver->resolve($field, $context, $info, $value, $args);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    

}