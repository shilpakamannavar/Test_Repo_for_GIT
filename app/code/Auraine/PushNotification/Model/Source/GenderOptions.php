<?php
namespace Auraine\PushNotification\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 */
class GenderOptions implements OptionSourceInterface
{
/**
 * Get the options
 *
 * @return array
 */
    public function toOptionArray()
    {
        return [
        ['label' => __('Select Gender'), 'value' => ''],
        ['label' => __('Male'), 'value' => 'Male'],
        ['label' => __('Female'), 'value' => 'Female']
        ];
    }
}
