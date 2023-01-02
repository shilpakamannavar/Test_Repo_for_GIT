<?php

namespace Auraine\BannerSlider\Ui\Component\DataProvider;


use Magento\Cms\Ui\Component\AddFilterInterface;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;

class FullTextFilter implements AddFilterInterface
{
    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * FullTextFilter constructor.
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        FilterBuilder $filterBuilder
    )
    {
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * Adds custom filter to search criteria builder based on received filter.
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Filter $filter
     * @return void
     */
    public function addFilter(SearchCriteriaBuilder $searchCriteriaBuilder, Filter $filter)
    {
        return;
    }
}