<?php
namespace Auraine\BannerSlider\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 */
class SliderOptions implements OptionSourceInterface
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
        ['label' => __('Carousel'), 'value' => 'carousel'],
        ['label' => __('Slider'), 'value' => 'slider'],
        ['label' => __('Banner'), 'value' => 'banner'],
        ['label' => __('Grid'), 'value' => 'grid'],
        ['label' => __('Secondary Carousel '), 'value' => 'secondary_carousel'],
        ['label' => __('Product Slider'), 'value' => 'product_slider'],
        ['label' => __('Look'), 'value' => 'look'],
        ['label' => __('Blog Slider'), 'value' => 'blog_slider'],
        ['label' => __('Category Circle'), 'value' => 'category_circle'],
        ['label' => __('Video Player'), 'value' => 'video_player'],
        ['label' => __('Image and Description'), 'value' => 'image_with_description'],
        ['label' => __('Image,Title and Description'), 'value' => 'image_top_description'],
        ['label' => __('Other'), 'value' => 'other']
        ];
    }
}
