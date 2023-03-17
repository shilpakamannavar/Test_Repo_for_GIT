<?php

declare(strict_types=1);

namespace Auraine\Offerlabel\Model\Resolver\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Model\Configuration;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * @inheritdoc
 */

class Quantity implements ResolverInterface
{

     /**
      * @var ScopeConfigInterface
      */
    private $scopeConfig;

    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StockRegistryInterface $stockRegistry
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StockRegistryInterface $stockRegistry
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->stockRegistry = $stockRegistry;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        /* @var $product ProductInterface */
     
        $product = $value['model'];
        $stockCurrentQty = 0;
        $stockCurrentQty = $this->stockRegistry->getStockStatus(
            $product->getId(),
            $product->getStore()->getWebsiteId()
        )->getQty();

        return (float)$stockCurrentQty;
     
    }

}
