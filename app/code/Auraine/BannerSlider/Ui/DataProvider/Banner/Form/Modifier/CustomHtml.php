<?php


namespace Auraine\BannerSlider\Ui\DataProvider\Banner\Form\Modifier;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;

class CustomHtml implements ModifierInterface
{

    /**
     * Modify Data
     *
     * @param array $data
     * @return array
     * @since 100.1.0
     */
    public function modifyData(array $data)
    {
        foreach ($data as &$item) {
            $resourcePath = $item['resource_path'] ?? null;
            $resourceType = $item['resource_type'];
            if ($resourcePath && $resourceType === 'custom_html') {
                unset($item['resource_path']);
                $item['resource_path_custom_html'] = $resourcePath;
            }
        }
        return $data;
    }

    /**
     * Modify Mobile Data
     *
     * @param array $data
     * @return array
     * @since 100.1.0
     */
    public function modifyDataMobile(array $data)
    {
        foreach ($data as &$item) {
            $resourcePathMobile = $item['resource_path_mobile'] ?? null;
            $resourceTypeMobile = $item['resource_type'];
            if ($resourcePathMobile && $resourceTypeMobile === 'custom_html') {
                unset($item['resource_path_mobile']);
                $item['resource_path_mobile_custom_html'] = $resourcePathMobile;
            }
        }
        return $data;
    }

    /**
     * Modify Meta
     *
     * @param array $meta
     * @return array
     * @since 100.1.0
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }
}
