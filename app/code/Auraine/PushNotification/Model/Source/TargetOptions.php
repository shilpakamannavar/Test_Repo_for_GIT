<?php
namespace Auraine\PushNotification\Model\Source;

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
        ['label' => __('Select Notification Type'), 'value' => ''],
        ['label' => __('Send Immediately'), 'value' => 'immediate'],
        ['label' => __('Schedule Push'), 'value' => 'schedule'],
        ['label' => __('Triggered on Event'), 'value' => 'trigger']
        ];
    }
}
