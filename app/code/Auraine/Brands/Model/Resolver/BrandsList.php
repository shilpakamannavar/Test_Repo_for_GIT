<?php
declare(strict_types=1);

namespace Auraine\Brands\Model\Resolver;


use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;

class BrandsList implements ResolverInterface
{
   /**
    * @param Field $field
    * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
    * @param ResolveInfo $info
    * @param array|null $value
    * @param array|null $args
    * @return array|\Magento\Framework\GraphQl\Query\Resolver\Value|mixed
    * @throws GraphQlInputException
    */
    private $dataProvider;
    
    public function __construct(
        \Auraine\Brands\Model\Resolver\DataProvider\BrandsList $dataProvider
    ) {
        $this->dataProvider = $dataProvider;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
         $filter_entity_id = $args['entity_id'] ?? null;
         $filter_label = $args['filter_label'] ?? null; 
         $filter_url = $args['url_key'] ?? null; 
                 
        return $this->dataProvider->getBrandsList($filter_entity_id,$filter_label, $filter_url);
    }
}
