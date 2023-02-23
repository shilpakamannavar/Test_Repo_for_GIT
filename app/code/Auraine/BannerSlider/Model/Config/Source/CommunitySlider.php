<?php

namespace Auraine\BannerSlider\Model\Config\Source;

use Auraine\BannerSlider\Api\SliderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Data\OptionSourceInterface;

class CommunitySlider implements OptionSourceInterface
{
    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;
    /**
     * @var SliderRepositoryInterface
     */
    private $sliderRepository;

    /**
     * ResourceMap constructor.
     * @param SliderRepositoryInterface $sliderRepository
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     */
    public function __construct(
        SliderRepositoryInterface $sliderRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    ) {
        $this->sliderRepository = $sliderRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $items = $this->sliderRepository->getList($this->getSearchCriteriaBuilder()->create())->getItems();
        $result = [];
        $emptyResult= [
            ['value' => '', 'label' => 'Select Slider'],
            ];
        foreach ($items as $item) {
            $result[] = [
                'label' => $item->getTitle(),
                'value' => $item->getEntityId()
            ];
        }
        return array_merge($emptyResult, $result);
    }

    /**
     * @return SearchCriteriaBuilder
     */
    protected function getSearchCriteriaBuilder()
    {
        return $this->searchCriteriaBuilderFactory->create();
    }
}
