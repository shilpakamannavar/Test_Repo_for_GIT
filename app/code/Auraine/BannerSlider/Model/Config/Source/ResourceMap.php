<?php

namespace Auraine\BannerSlider\Model\Config\Source;


use Auraine\BannerSlider\Api\ResourceMapRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Data\OptionSourceInterface;

class ResourceMap implements OptionSourceInterface
{
    /**
     * @var ResourceMapRepositoryInterface
     */
    private $resourceMapRepository;
    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;

    /**
     * ResourceMap constructor.
     * @param ResourceMapRepositoryInterface $resourceMapRepository
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     */
    public function __construct(
        ResourceMapRepositoryInterface $resourceMapRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    )
    {
        $this->resourceMapRepository = $resourceMapRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $items = $this->resourceMapRepository->getList($this->getSearchCriteriaBuilder()->create())->getItems();
        $result = [];
        foreach ($items as $item) {
            $result[] = [
                'label' => $item->getTitle(),
                'value' => $item->getEntityId()
            ];
        }
        return $result;
    }

    /**
     * @return SearchCriteriaBuilder
     */
    protected function getSearchCriteriaBuilder()
    {
        return $this->searchCriteriaBuilderFactory->create();
    }
}