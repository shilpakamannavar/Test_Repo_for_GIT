<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Brands\Model\Resolver;

use Magento\CatalogGraphQl\DataProvider\Product\LayeredNavigation\LayerBuilder;
use Magento\CatalogGraphQl\DataProvider\Product\LayeredNavigation\Builder\Aggregations\Category;
use Magento\Directory\Model\PriceCurrency;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Store\Api\Data\StoreInterface;
use Auraine\Brands\Model\ResourceModel\Brands\CollectionFactory;

/**
 * Layered navigation filters resolver, used for GraphQL request processing.
 */
class Aggregations implements ResolverInterface
{
    /**
     * @var Layer\DataProvider\Filters
     */
    private $filtersDataProvider;

    /**
     * @var LayerBuilder
     */
    private $layerBuilder;

    /**
     * @var PriceCurrency
     */
    private $priceCurrency;

    /**
     * @var
     */
    private $brandsFactory;
      /**
     * @var
     */
    private $objectManager;
    /**
     * @var Category\IncludeDirectChildrenOnly
     */
    private $includeDirectChildrenOnly;

    /**
     * @param \Magento\CatalogGraphQl\Model\Resolver\Layer\DataProvider\Filters $filtersDataProvider
     * @param LayerBuilder $layerBuilder
     * @param PriceCurrency $priceCurrency
     * @param Category\IncludeDirectChildrenOnly $includeDirectChildrenOnly
     */
    public function __construct(
        \Magento\CatalogGraphQl\Model\Resolver\Layer\DataProvider\Filters $filtersDataProvider,
        LayerBuilder $layerBuilder,
        PriceCurrency $priceCurrency = null,
        Category\IncludeDirectChildrenOnly $includeDirectChildrenOnly = null,
        CollectionFactory $brandsFactory = null ,
    ) {
        $this->filtersDataProvider = $filtersDataProvider;
        $this->layerBuilder = $layerBuilder;
        $this->priceCurrency = $priceCurrency ?: ObjectManager::getInstance()->get(PriceCurrency::class);
        $this->includeDirectChildrenOnly = $includeDirectChildrenOnly
            ?: ObjectManager::getInstance()->get(Category\IncludeDirectChildrenOnly::class);
        $this->_brandsFactory  = $brandsFactory ?: ObjectManager::getInstance()->get(\Auraine\Brands\Model\Product\Attribute\Source\BrandAttrProd::class);
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
        if (!isset($value['layer_type']) || !isset($value['search_result'])) {
            return null;
        }

        $aggregations = $value['search_result']->getSearchAggregation();

        if ($aggregations) {
           
            /** @var StoreInterface $store */
            $store = $context->getExtensionAttributes()->getStore();
            $storeId = (int)$store->getId();
            $results = $this->layerBuilder->build($aggregations, $storeId);
            if (isset($results['brand_name_bucket'])) {
                $results['brand_name_bucket']['label'] = 'Brand';
                $results['brand_name_bucket']['attribute_code'] = 'brand_name';
                foreach ($results['brand_name_bucket']['options'] as &$value) {
                    $id = $value['value'];
                    $result = $this->_brandsFactory->getBrandDataById($id);
                    if (!empty($result)){
                        $value['label'] = $result[0]['label'];
                        $value['value'] = $result[0]['value'];
                    }
                }
            }
           
            return $results;
        } else {
            return [];
        }
    }
}
