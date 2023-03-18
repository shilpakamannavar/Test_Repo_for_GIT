<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\SwatchData\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Swatches\Helper\Data;
use Magento\Framework\App\Config\ScopeConfigInterface ;

/**
 * @inheritdoc
 */
class CustomerDataProvider implements ResolverInterface
{
    /**
     * swatchHelper is the helper libraray for swatch data
     *
     * @var $swatchHelper
     */
    private $swatchHelper;

    /**
     *
     * @var $scopeConfig
     */
    private $scopeConfig;

    /**
     *
     * @param Data $swatchHelper
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Data $swatchHelper,
        ScopeConfigInterface $scopeConfig,
    ) {
        $this->swatchHelper = $swatchHelper;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Fetch and format configurable variants.
     *
     * @param Field $field
     * @param [type] $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return void
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if ($value['label'] == 'Color') {
            
            $hexcodeData = $this->swatchHelper->getSwatchesByOptionsId([$value['value']]);
            $typeName = $this->getswatchType($hexcodeData[$value['value']]['type']);
            $hexCode =  $hexcodeData[$value['value']]['value'];
            
            if ($typeName == 'ImageSwatchData') {
                $url = $this->scopeConfig->getValue(
                    'swatch_data/general/swatch_data_base_url',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );
                $hexCode = $url.$hexCode;
            }
            
            return [
                'type' => $typeName,
                'value' =>  $hexCode,
                ];
        }

                    return null;
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
