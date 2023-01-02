<?php
declare(strict_types=1);

namespace Auraine\BannerSlider\Model\Resolver;


use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;

class SliderList implements ResolverInterface
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
        \Auraine\BannerSlider\Model\Resolver\DataProvider\SliderList $dataProvider
    ) {
        $this->dataProvider = $dataProvider;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        
        return $this->dataProvider->getSliderList();
    }
}
