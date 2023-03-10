<?php

declare(strict_types=1);

namespace Auraine\ExtendCatalogGraphQl\Plugin\Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Product\CollectionProcessor;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Product\CollectionProcessor\StockProcessor as Stock;

class StockProcessor
{
    public function aroundProcess(
        Stock $subject,
        \Closure $proceed,
        Collection $collection,
        SearchCriteriaInterface $searchCriteria,
        array $attributeNames
    ) {
        $stockFlag = 'has_stock_status_filter';
        if (!$collection->hasFlag($stockFlag)) {
            $collection->setFlag($stockFlag, true);
        }

        return $collection;
    }
}

