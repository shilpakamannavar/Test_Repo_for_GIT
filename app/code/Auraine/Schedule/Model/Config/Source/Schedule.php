<?php

namespace Auraine\Schedule\Model\Config\Source;

use Auraine\Schedule\Api\ScheduleRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Data\OptionSourceInterface;

class Schedule implements OptionSourceInterface
{
    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;
    /**
     * @var ScheduleRepositoryInterface
     */
    private $scheduleRepository;

    /**
     * ResourceMap constructor.
     * @param ScheduleRepositoryInterface $scheduleRepository
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     */
    public function __construct(
        ScheduleRepositoryInterface $scheduleRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    ) {
        $this->scheduleRepository = $scheduleRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $items = $this->scheduleRepository->getList($this->getSearchCriteriaBuilder()->create())->getItems();
        $result = [];
        $default_res= [
            ['value' => '', 'label' => 'Select banner'],
            ];
        foreach ($items as $item) {
            $result[] = [
                'label' => $item->getTitle(),
                'value' => $item->getEntityId()
            ];
        }
        return array_merge($default_res, $result);
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
