<?php
namespace Auraine\CustomGraphql\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class Getproductslist implements ResolverInterface
{
    private $wordofthedayDataProvider;
    /**
     * @param DataProvider\Wordoftheday $wordofthedayRepository
     */
    public function __construct(
        \Auraine\CustomGraphql\Model\Resolver\DataProvider\Getproductslist $wordofthedayDataProvider
    ) {
        $this->wordofthedayDataProvider = $wordofthedayDataProvider;
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
        //by Date
        $attribute_type = $this->getAttributeType( $args);
        $category_id = $this->getCategoryId( $args);
        $wordofthedayData = $this->wordofthedayDataProvider->getProductsByAttribute( $attribute_type, $category_id);
        return $wordofthedayData;
    }

    private function getAttributeType(array $args)
    {
        if (!isset($args['attribute_type'])) {
            throw new GraphQlInputException(__('"Attribute Type Should Be Specified'));
        }
        
        return $args['attribute_type'];
    }
    private function getCategoryId(array $args)
    {
        if (!isset($args['category_id'])) {
            throw new GraphQlInputException(__('"Category Id Should Be Specified'));
        }
        
        return $args['category_id'];
    }
}
