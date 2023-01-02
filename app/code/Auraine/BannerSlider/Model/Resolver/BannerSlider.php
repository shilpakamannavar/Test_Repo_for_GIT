<?php

namespace Auraine\BannerSlider\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Auraine\BannerSlider\Model\Resolver\DataProvider\Slider as SliderDataProvider;

class BannerSlider implements ResolverInterface
{
    /**
     * @var SliderDataProvider
     */
    private $sliderDataResolver;

    /**
     * BannerSlider constructor.
     * @param SliderDataProvider $sliderDataResolver
     */
    public function __construct(
        SliderDataProvider $sliderDataResolver
    )
    {
        $this->sliderDataResolver = $sliderDataResolver;
    }

    /**
     * Fetches the data from persistence models and format it according to the GraphQL schema.
     *
     * @param \Magento\Framework\GraphQl\Config\Element\Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @throws \Exception
     * @return mixed|Value
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    )
    {
        return $this->sliderDataResolver->getData($args['sliderId']);
    }
}