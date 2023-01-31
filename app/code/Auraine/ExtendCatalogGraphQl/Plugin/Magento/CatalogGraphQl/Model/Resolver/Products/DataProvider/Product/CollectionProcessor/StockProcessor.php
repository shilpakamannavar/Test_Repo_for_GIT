<?php

declare(strict_types=1);

namespace Auraine\ExtendCatalogGraphQl\Plugin\Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Product\CollectionProcessor;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;

class StockProcessor
{
    public function aroundProcess(
        \Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Product\CollectionProcessor\StockProcessor $subject,
        \Closure $proceed,
        Collection $collection,
        SearchCriteriaInterface $searchCriteria,
        array $attributeNames
    ) {
        $stockFlag = 'has_stock_status_filter';
        if (!$collection->hasFlag($stockFlag)) {
            // Removed bellow line for fetching out of stock variant graphql data
            //$this->stockStatusResource->addStockDataToCollection($collection, !$this->stockConfig->isShowOutOfStock());
            $collection->setFlag($stockFlag, true);
        }

        return $collection;
    }
}

