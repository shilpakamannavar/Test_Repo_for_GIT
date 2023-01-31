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
        ['label' => __('Homepage'), 'value' => 'home_page'],
        ['label' => __('CLP'), 'value' => 'clp'],
        ['label' => __('PLP'), 'value' => 'plp'],
        ['label' => __('BLP'), 'value' => 'blp'],
        ['label' => __('Community landing page'), 'value' => 'community_landing_page'],
        ['label' => __('Community blog page'), 'value' => 'community_blog_page'],
        ['label' => __('Offer page'), 'value' => 'offer_page'],
        ['label' => __('Gift page'), 'value' => 'gift_page']
        ];
    }
}
