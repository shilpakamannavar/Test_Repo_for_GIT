<?php
namespace Auraine\BannerSlider\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
* Class Status
*/
class PageOptions implements OptionSourceInterface
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
        ['label' => __('Home page'), 'value' => 'home_page'],
        ['label' => __('Category Page'), 'value' => 'category_page'],
        ['label' => __('Brand Page'), 'value' => 'brand_page']
    ];
}
}