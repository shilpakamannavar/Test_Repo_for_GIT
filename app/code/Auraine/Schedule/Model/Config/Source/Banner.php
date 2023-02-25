<?php

namespace Auraine\Schedule\Model\Config\Source;

use Auraine\BannerSlider\Api\BannerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Data\OptionSourceInterface;

class Banner implements OptionSourceInterface
{
    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;
    /**
     * @var BannerRepositoryInterface
     */
    private $bannerRepository;

    /**
     * ResourceMap constructor.
     * @param BannerRepositoryInterface $bannerRepository
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     */
    public function __construct(
        BannerRepositoryInterface $bannerRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    ) {
        $this->bannerRepository = $bannerRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $items = $this->bannerRepository->getList($this->getSearchCriteriaBuilder()->create())->getItems();
        $result = [];
        $defaultRes= [
            ['value' => '', 'label' => 'Select Banner'],
            ];
        foreach ($items as $item) {
            $result[] = [
                'label' => $item->getTitle(),
                'value' => $item->getEntityId()
            ];
        }
        return array_merge($defaultRes, $result);
    }

    /**
     * Create Search Criteria factory
     *
     * @return SearchCriteriaBuilder
     */
    protected function getSearchCriteriaBuilder()
    {
        return $this->searchCriteriaBuilderFactory->create();
    }
}
