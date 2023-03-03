<?php
namespace Auraine\BannerSlider\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 */
class VideoOption implements OptionSourceInterface
{
/**
 * Get the options
 *
 * @return array
 */
    public function toOptionArray()
    {
        return [
        ['label' => __('Select Type'), 'value' => ''],
        ['label' => __('Youtube Video'), 'value' => 'youtube_video'],
        ['label' => __('S3 Video'), 'value' => 's3_video']
        ];
    }
}
