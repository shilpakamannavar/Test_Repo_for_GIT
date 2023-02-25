<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Auraine\Schedule\Controller\Adminhtml\Schedule;

use Auraine\Schedule\Api\ScheduleRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
{
    private DataPersistorInterface $dataPersistor;
    private ScheduleRepositoryInterface $scheduleRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private FilterBuilder $filterBuilder;
    private FilterGroupBuilder $filterGroupBuilder;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param ScheduleRepositoryInterface $scheduleRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param FilterGroupBuilder $filterGroupBuilder
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        ScheduleRepositoryInterface $scheduleRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->scheduleRepository = $scheduleRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $schedules = $this->scheduleRepository->getList($searchCriteria);

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        $id = (int) $this->getRequest()->getParam('schedule_id');
        foreach ($schedules->getItems() as $schedule) {
            if ($schedule->getOldBannerId() == $data['old_banner_id'] &&
                $schedule->getNewBannerId() == $data['new_banner_id'] && empty($id)) {
                $this->messageManager->addErrorMessage(__('This Schedule  exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        }

        $model = $this->_objectManager->create(\Auraine\Schedule\Model\Schedule::class)->load($id);
        if (!$model->getId() && $id) {
            $this->messageManager->addErrorMessage(__('This Schedule no longer exists.'));
            return $resultRedirect->setPath('*/*/');
        }

        if ($data['old_banner_id'] == $data['new_banner_id']) {
            $this->messageManager->addErrorMessage('Banners should not be same choose different banners');
            $this->dataPersistor->set('auraine_schedule_schedule', $data);
            return $resultRedirect->setPath('*/*/edit', ['schedule_id' => $id]);
        }

        $model->setData($data);

        try {
            $this->scheduleRepository->save($model);
            $this->messageManager->addSuccessMessage(__('You saved the Schedule.'));
            $this->dataPersistor->clear('auraine_schedule_schedule');
        
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['schedule_id' => $model->getId()]);
            }
            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('Something went wrong while saving the Schedule.')
            );
        }
    }
}
