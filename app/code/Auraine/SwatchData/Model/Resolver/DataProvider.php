<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\SwatchData\Model\Resolver;


use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface ;
use Magento\Swatches\Helper\Data;

/**
 * @inheritdoc
 */

class DataProvider implements ResolverInterface
{
     /**
     * swatchHelper is the helper libraray for swatch data
     *
     * @var $swatchHelper
     */
    private $swatchHelper;

    /**
     *
     * @param ScopeConfigInterface $scopeConfig
     */

    private $_scopeConfig;

    public function __construct(
        Data $swatchHelper,
        ScopeConfigInterface $scopeConfig,
    ) {
        $this->swatchHelper = $swatchHelper;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Fetch and format configurable variants.
     *
     * @param Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array|\Magento\Framework\GraphQl\Query\Resolver\Value|mixed
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if($value['option_label'] == 'Color'){

            $hexCodeData = $this->swatchHelper->getSwatchesByOptionsId([$value['value_id']]);
            $typeName = $this->getswatchType($hexCodeData[$value['value_id']]['type']);
            $hexCode =  $hexCodeData[$value['value_id']]['value'];
            if($typeName == 'ImageSwatchData') {
                $url = $this->_scopeConfig->getValue('swatch_data/general/swatch_data_base_url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                $hexCode = $url.$hexCode;
            }
            return  [
            'type' => $typeName,
            'value' =>  $hexCode,
            ];
        }
        
       return null;
    }
    
     /**
     *This will return type of swatch by id
     *
     * @param id $valueType
     * @return string
     */
    private function getswatchType($valueType)
    {
        switch ($valueType) {
            case 0:
                return 'TextSwatchData';
            case 1:
                return 'ColorSwatchData';
            case 2:
                return 'ImageSwatchData';
            break;
        }
    }
}
