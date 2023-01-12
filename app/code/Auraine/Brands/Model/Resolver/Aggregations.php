<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Brands\Model\Resolver;

use Magento\CatalogGraphQl\DataProvider\Product\LayeredNavigation\LayerBuilder;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Layered navigation filters resolver, used for GraphQL request processing.
 */
class Aggregations implements ResolverInterface
{
    
    /**
     * @var LayerBuilder
     */
    private $layerBuilder;

    private $_brandsFactory;

    /**
     * @param LayerBuilder $layerBuilder
     * @param \Auraine\Brands\Model\Product\Attribute\Source\BrandAttrProd
     */
    public function __construct(
        LayerBuilder $layerBuilder,
        \Auraine\Brands\Model\Product\Attribute\Source\BrandAttrProd $brandsFactory
    ) {
        $this->layerBuilder = $layerBuilder;
        $this->_brandsFactory  = $brandsFactory;
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
                    $id = (int)$value['value'];

                    $result = $this->_brandsFactory->getAllBrandDataById($id);
                    if (!empty($result)){
                        $value['label'] = $result[0]['label'];
                        $value['value'] = $result[0]['value'];
                    }

                }
            }

            return $results;
        } 

        return [];
    }
}
