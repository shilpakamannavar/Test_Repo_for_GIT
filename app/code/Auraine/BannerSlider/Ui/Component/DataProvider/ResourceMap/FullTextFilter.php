<?php

namespace Auraine\BannerSlider\Ui\Component\DataProvider\ResourceMap;


use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;

class FullTextFilter extends \Auraine\BannerSlider\Ui\Component\DataProvider\FullTextFilter
{
    public function addFilter(SearchCriteriaBuilder $searchCriteriaBuilder, Filter $filter)
    {
        $titleFilter = $this->filterBuilder->setField('title')
            ->setValue(sprintf('%%%s%%', $filter->getValue()))
            ->setConditionType('like')
            ->create();
        $searchCriteriaBuilder->addFilter($titleFilter);
    }
}