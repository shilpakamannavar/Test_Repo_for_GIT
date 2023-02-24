<?php

namespace Auraine\Schedule\Cron;

use Auraine\Schedule\Api\Data\ScheduleInterface;
use Auraine\Schedule\Api\ScheduleRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Auraine\BannerSlider\Api\BannerRepositoryInterface;

class BannerSchedular
{
    protected $scheduleRepository;
    protected $scheduleInterface;
    protected $searchCriteriaBuilder;
    protected $filterBuilder;
    protected $filterGroupBuilder;
    protected $bannerRepositoryInterface;

    public function __construct(
        ScheduleRepositoryInterface $scheduleRepository,
        ScheduleInterface $scheduleInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        BannerRepositoryInterface $bannerRepositoryInterface
        )
    {
        $this->scheduleRepository = $scheduleRepository;
        $this->scheduleInterface = $scheduleInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->bannerRepositoryInterface = $bannerRepositoryInterface;
    }

    /**
     * Update status of banner scheduler based on start and end dates.
     *
     * @return void
     */
    public function execute()
    {
        $attr3 = $this->filterBuilder->setField('status')
                        ->setValue('Pending')
                        ->setConditionType('eq')
                        ->create();
        $attr4 = $this->filterBuilder->setField('status')
                    ->setValue('Active')
                    ->setConditionType('eq')
                    ->create();
        $attr5 = $this->filterBuilder->setField('status')
                    ->setValue('Inactive')
                    ->setConditionType('eq')
                    ->create();
        $filterOr = $this->filterGroupBuilder
                    ->addFilter($attr3)
                    ->addFilter($attr4)
                    ->addFilter($attr5)
                    ->create();
        $this->searchCriteriaBuilder->setFilterGroups([$filterOr]);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $schedules = $this->scheduleRepository->getList($searchCriteria);
        foreach ($schedules->getItems() as $schedule) {
            $startDate = $schedule->getStartDate();
            $endDate = $schedule->getEndDate();
            $status = $schedule->getStatus();
            $oldBannerId = $schedule->getOldBannerId();
            $newBannerId = $schedule->getNewBannerId();
            if ($startDate && $endDate) {
                $currentDate = date('Y-m-d H:i:s');
                if ($currentDate >= $startDate && $currentDate <= $endDate) {
                    if ($status != 'Active') {
                        $schedule->setStatus('Active');
                       $oldBanner =  $this->bannerRepositoryInterface->loadById($oldBannerId);
                       $oldBanner->setIsEnabled(0);
                       $oldBanner->save();

                       $newBanner =  $this->bannerRepositoryInterface->loadById($newBannerId);
                       $newBanner->setIsEnabled(1);
                       $newBanner->save();
                       
                       try {
                          $this->scheduleRepository->save($schedule);
                        } catch (LocalizedException $e) {
                            // log the exception message
                        }
                    }
                } elseif ($currentDate < $startDate) {
                    if ($status != 'Pending') {
                        $schedule->setStatus('Pending');
                        try {
                            $this->scheduleRepository->save($schedule);
                        } catch (LocalizedException $e) {
                            // log the exception message
                        }
                    }
                } else {
                    if ($status != 'Inactive') {
                        $schedule->setStatus('Inactive');
                        $newBanner =  $this->bannerRepositoryInterface->loadById($newBannerId);
                        $newBanner->setIsEnabled(0);
                        $newBanner->save();
                        try {
                            $this->scheduleRepository->save($schedule);
                        } catch (LocalizedException $e) {
                            // log the exception message
                        }
                    }
                }
            }
        }
    }
}
