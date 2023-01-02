<?php


namespace Auraine\BannerSlider\Model\Config\Source\Widget;


class Slider extends \Auraine\BannerSlider\Model\Config\Source\Slider
{
    /**
     * @return \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected function getSearchCriteriaBuilder()
    {
        $searchCriteriaBuilder = parent::getSearchCriteriaBuilder();
        $searchCriteriaBuilder->addFilter('is_enabled', '1', 'eq');
        return $searchCriteriaBuilder;
    }
}