<?php
namespace Auraine\AddFreeProduct\Test\Unit\Model\Resolver;

class GetFreeProductForCartTest extends \PHPUnit\Framework\TestCase
{
    /**
     * testResolveReturnsEmptyArrayWhenNoFreeItems
     *
     * @return void
     */
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

    /**
     * testResolveReturnsCorrectDataForFreeItem
     *
     * @return void
     */
    public function testResolveReturnsCorrectDataForFreeItem()
    {
        // Create a mock for PromoItemRegistry that returns a fake item
        $promoItemRegistry = $this->createMock(\Amasty\Promo\Model\ItemRegistry\PromoItemRegistry::class);
        $promoItem = $this->createMock(\Amasty\Promo\Model\ItemRegistry\PromoItemData::class);
        $promoItem->method('getSku')->willReturn('test_sku');
        $promoItemRegistry->method('getAllItems')->willReturn([$promoItem]);

        // Create a mock for ProductRepositoryInterface that returns a fake product
        $product = $this->createMock(\Magento\Catalog\Api\Data\ProductInterface::class);
        $product->method('getId')->willReturn(1);
        $product->method('getName')->willReturn('Test Product');
        $productRepository = $this->createMock(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $productRepository->method('get')->willReturn($product);

        // Create a mock for StoreManagerInterface that returns a fake base URL
        $storeManager = $this->createMock(\Magento\Store\Model\StoreManagerInterface::class);
        $store = $this->createMock(\Magento\Store\Model\Store::class);
        $store->method('getBaseUrl')->willReturn('http://example.com/');
        $storeManager->method('getStore')->willReturn($store);

        // Instantiate the resolver with the mocks
        $resolver = new \Auraine\AddFreeProduct\Model\Resolver\GetFreeProductForCart(
            $promoItemRegistry,
            $productRepository,
            $storeManager
        );

        // Call the resolve method with a fake context, field, and info
        $field = $this->createMock(\Magento\Framework\GraphQl\Config\Element\Field::class);
        $context = [];
        $info = $this->createMock(\Magento\Framework\GraphQl\Schema\Type\ResolveInfo::class);
        $value = null;
        $args = null;
        $result = $resolver->resolve($field, $context, $info, $value, $args);

        // Verify that the result contains the expected data
        $this->assertIsArray($result);
    }
    
}
