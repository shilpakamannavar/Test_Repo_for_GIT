<?php
namespace Auraine\BannerSlider\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 */
class TargetOptions implements OptionSourceInterface
{
/**
 * Get the options
 *
 * @return array
 */
    public function toOptionArray()
    {
        return [
        ['label' => __('Select Type (this is for app only)'), 'value' => ''],
        ['label' => __('PLP'), 'value' => 'plp'],
        ['label' => __('PDP'), 'value' => 'pdp'],
        ['label' => __('CLP'), 'value' => 'clp'],
        ['label' => __('Home'), 'value' => 'home'],
        ['label' => __('Brand'), 'value' => 'brand'],
        ['label' => __('others '), 'value' => 'others']
        ];
    }
}
