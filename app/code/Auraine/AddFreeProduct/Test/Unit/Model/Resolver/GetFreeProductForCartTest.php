<?php
declare(strict_types=1);

namespace Auraine\AddFreeProduct\Test\Unit\Model\Resolver;

use Amasty\Promo\Model\ItemRegistry\PromoItemRegistry;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;
use Auraine\AddFreeProduct\Model\Resolver\GetFreeProductForCart;

class GetFreeProductForCartTest extends TestCase
{
    /**
     * @var GetFreeProductForCart
     */
    private $model;

    /**
     * @var PromoItemRegistry|\PHPUnit\Framework\MockObject\MockObject
     */
    private $promoItemRegistryMock;

    /**
     * @var ProductRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $productRepositoryMock;

    /**
     * @var StoreManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $storeManagerMock;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->promoItemRegistryMock = $this->createMock(PromoItemRegistry::class);
        $this->productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $this->storeManagerMock = $this->createMock(StoreManagerInterface::class);

        $objectManager = new ObjectManager($this);
        $this->model = $objectManager->getObject(GetFreeProductForCart::class, [
            'promoItemRegistry' => $this->promoItemRegistryMock,
            'productRepository' => $this->productRepositoryMock,
            'storeManager' => $this->storeManagerMock
        ]);
    }

    /**
     * Test case for GetFreeProductForCart::resolve()
     */
    public function testResolve(): void
    {
        // Prepare test data
        $sku = 'some-sku';
        $imageUrl = 'some-image-url';
        $expectedResult = [
            [
                'id' => 1,
                'sku' => $sku,
                'title' => 'Some product name',
                'isPromoItems' => true,
                'image' => 'https://www.example.com/media/catalog/product' . $imageUrl
            ]
        ];

        // Mock dependencies
        $promoItemMock = $this->createMock(\Amasty\Promo\Model\ItemRegistry\PromoItemData::class);
        $productMock = $this->createMock(\Magento\Catalog\Model\Product::class);
        $storeMock = $this->createMock(\Magento\Store\Model\Store::class);

        // Configure the mocks
        $promoItemMock->expects($this->once())
            ->method('isDeleted')
            ->willReturn(true);
        $promoItemMock->expects($this->any())
            ->method('getSku')
            ->willReturn($sku);

        $this->promoItemRegistryMock->expects($this->any())
            ->method('getAllItems')
            ->willReturn([$promoItemMock]);

        $this->productRepositoryMock->expects($this->any())
            ->method('get')
            ->with($sku)
            ->willReturn($productMock);

        $productMock->expects($this->any())
            ->method('getId')
            ->willReturn(1);

        $productMock->expects($this->any())
            ->method('getName')
            ->willReturn('Some product name');
        $productMock->expects($this->any())
            ->method('getImage')
            ->willReturn($imageUrl);

        $this->storeManagerMock->expects($this->any())
            ->method('getStore')
            ->willReturn($storeMock);

        $storeMock->expects($this->any())
            ->method('getBaseUrl')
            ->willReturn('https://www.example.com/');

        $field = $this->createMock(\Magento\Framework\GraphQl\Config\Element\Field::class);
        $resolverInfo = $this->createMock(\Magento\Framework\GraphQl\Schema\Type\ResolveInfo::class);

        // Invoke the method being tested
        $result = $this->model->resolve($field, [], $resolverInfo, [], [], []);

        // Assertions
        $this->assertEquals($expectedResult, $result);
    }
}
