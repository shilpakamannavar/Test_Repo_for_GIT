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
    * @var $dataProvider
    */
    private $dataProvider;
    
    /**
     * Slider List Constructor.
     *
     * @param \Auraine\BannerSlider\Model\Resolver\DataProvider\SliderList $dataProvider
     */
    public function __construct(
        \Auraine\BannerSlider\Model\Resolver\DataProvider\SliderList $dataProvider
    ) {
        $this->dataProvider = $dataProvider;
    }

    /**
     * Checking for Entity ID
     *
     * @param Field $field
     * @param context $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return void
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $filter_entity_id = $args['entity_id'] ?? null;

        return $this->dataProvider->getSliderList($filter_entity_id);
    }
}
