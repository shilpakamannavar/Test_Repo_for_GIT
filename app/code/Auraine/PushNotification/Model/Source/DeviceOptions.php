<?php
namespace Auraine\PushNotification\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 */
class DeviceOptions implements OptionSourceInterface
{
/**
 * Get the options
 *
 * @return array
 */
    public function toOptionArray()
    {
        return [
        ['label' => __('Select Device Type'), 'value' => ''],
        ['label' => __('Android'), 'value' => 'Android'],
        ['label' => __('IOS'), 'value' => 'IOS']
        ];
    }
}
