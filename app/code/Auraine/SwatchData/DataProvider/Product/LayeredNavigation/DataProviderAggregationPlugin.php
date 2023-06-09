<?php

namespace Auraine\SwatchData\DataProvider\Product\LayeredNavigation;

use Magento\Eav\Model\Config;
use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Swatches\Block\LayeredNavigation\RenderLayered;
use Magento\Swatches\Helper\Data;
use Psr\Log\LoggerInterface;
use Magento\CatalogGraphQl\DataProvider\Product\LayeredNavigation\LayerBuilder;
use Magento\CatalogGraphQl\DataProvider\Product\LayeredNavigation\LayerBuilderInterface;
use Magento\Framework\Exception\LocalizedException;

class DataProviderAggregationPlugin extends LayerBuilder implements LayerBuilderInterface
{
    /**
     * @var $builders
     */
    private $builders;
    /**
     * @var $logger
     */
    protected $logger;
    /**
     * @var $eavConfig
     */
    protected $eavConfig;
    /**
     * @var $swatchHelper
     */
    private $swatchHelper;
    /**
     * @var $renderLayered
     */
    private $renderLayered;
    /**
     * @var $scopeConfig
     */
    private $scopeConfig;

    /**
     * Constructor
     *
     * @param array $builders
     * @param LoggerInterface $logger
     * @param Config $eavConfig
     * @param Data $swatchHelper
     * @param RenderLayered $renderLayered
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */

    public function __construct(
        array $builders,
        LoggerInterface $logger,
        Config $eavConfig,
        Data $swatchHelper,
        RenderLayered $renderLayered,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->builders = $builders;
        $this->logger = $logger;
        $this->eavConfig = $eavConfig;
        $this->swatchHelper = $swatchHelper;
        $this->renderLayered = $renderLayered;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Build
     *
     * @param AggregationInterface $aggregation
     * @param int $storeId
     */

    public function build(
        AggregationInterface $aggregation,
        ?int $storeId
    ): array {
        $layers = [];
        foreach ($this->builders as $builder) {
            $layers[] = $builder->build($aggregation, $storeId);
        }
        $layers = \array_merge(...$layers);
        foreach ($layers as $key => $value) {
            $count = count($layers[$key]['options']);
            $attribute = $this->eavConfig->getAttribute('catalog_product', $layers[$key]['attribute_code']);
            if ($this->swatchHelper->isSwatchAttribute($attribute)) {
                for ($i = 0; $i < $count; $i++) {
                    $hexcodeData = $this->swatchHelper->getSwatchesByOptionsId([$layers[$key]['options'][$i]['value']]);
                    if ($hexcodeData) {
                        $typeName = $this->getswatchType($hexcodeData[$layers[$key]['options'][$i]['value']]['type']);
                        $swatchDataBaseUrl = $this->scopeConfig->getValue(
                            'swatch_data/general/swatch_data_base_url',
                            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                        );
                        $values = ($hexcodeData[$layers[$key]['options'][$i]['value']]['type'] == 2) ?
                         $swatchDataBaseUrl.$hexcodeData[$layers[$key]['options'][$i]['value']]['value'] :
                         $hexcodeData[$layers[$key]['options'][$i]['value']]['value'];
                        $temp = [
                            'type' => $typeName,
                            'value' => $values
                        ];
                        $layers[$key]['options'][$i]['swatch_data'] = $temp;
                    }
                }
            }
        }

        return \array_filter($layers);
    }

    /**
     * This will return type of swatch by id
     *
     * @param id $valueType
     * @return string
     */

    public function getswatchType($valueType)
    {
        $value = null ;
        switch ($valueType) {
            case 0:
                $value = 'TextSwatchData';
                break;
            case 1:
                $value = 'ColorSwatchData';
                break;
            case 2:
                $value = 'ImageSwatchData';
                break;
            default:
                break;
        }
        return $value ;
    }
}
