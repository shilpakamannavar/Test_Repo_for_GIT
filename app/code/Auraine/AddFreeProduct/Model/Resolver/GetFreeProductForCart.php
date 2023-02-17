<?php
namespace Auraine\AddFreeProduct\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;
use Magento\Quote\Model\Cart\AddProductsToCart as AddProductsToCartService;
use Magento\Quote\Model\Cart\Data\AddProductsToCartOutput;
use Magento\Quote\Model\Cart\Data\CartItemFactory;
use Magento\Quote\Model\QuoteMutexInterface;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
use Magento\Quote\Model\Cart\Data\Error;
use Magento\QuoteGraphQl\Model\CartItem\DataProvider\Processor\ItemDataProcessorInterface;

class GetFreeProductForCart implements ResolverInterface
{
    /**
     * @var \Amasty\Promo\Model\ItemRegistry\PromoItemRegistry
     */
    private $promoItemRegistry;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $_storeManager;

    /**
     * @param \Amasty\Promo\Model\ItemRegistry\PromoItemRegistry $promoItemRegistry
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Amasty\Promo\Model\ItemRegistry\PromoItemRegistry $promoItemRegistry,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->promoItemRegistry = $promoItemRegistry;
        $this->productRepository = $productRepository;
        $this->_storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $freeItems = $this->promoItemRegistry->getAllItems();
        $data = [];
        
        foreach ($freeItems as $item) {
            if ($item->isDeleted()) {
                $product = $this->productRepository->get($item->getSku());
                $ImageUrl = $product->getImage() ?? '';

                $data [] = [
                    'id' => $product->getId(),
                    'sku' => $item->getSku(),
                    'title' => $product->getName(),
                    'image' => $this->_storeManager
                        ->getStore()
                        ->getBaseUrl() . 'media/catalog/product'.$ImageUrl
                ];
            }
        }

        return $data;
    }
}
