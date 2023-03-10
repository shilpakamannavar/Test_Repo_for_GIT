<?php
namespace Auraine\BannerSlider\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 */
class DisplayOptions implements OptionSourceInterface
{
/**
 * Get the options
 *
 * @return array
 */
    public function toOptionArray()
    {
        return [
        ['label' => __('Both'), 'value' => 'both'],
        ['label' => __('Web'), 'value' => 'web'],
        ['label' => __('Mobile'), 'value' => 'mobile'],
        ];
    }
}
