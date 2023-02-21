<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Schedule\Controller\Adminhtml\Schedule;

use Magento\Framework\Exception\LocalizedException;
use Auraine\Schedule\Api\ScheduleRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder; 
use Magento\Framework\Api\Search\FilterGroupBuilder; 

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;
    protected $scheduleRepository;
    protected $searchCriteriaBuilder;
    protected $filterBuilder;
    protected $filterGroupBuilder;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        ScheduleRepositoryInterface $scheduleRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
        $this->scheduleRepository = $scheduleRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $schedules = $this->scheduleRepository->getList($searchCriteria);
       
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('schedule_id');
            
            foreach($schedules->getItems() as $schedule){
               if ($schedule->getOldBannerId() == $data['old_banner_id'] && $schedule->getNewBannerId() == $data['new_banner_id'] && empty($id)){
                    $this->messageManager->addErrorMessage(__('This Schedule  exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }
    
            $model = $this->_objectManager->create(\Auraine\Schedule\Model\Schedule::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Schedule no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            if ($data['old_banner_id'] == $data['new_banner_id']){
                $this->messageManager->addErrorMessage('Banners should not be same choose different banners');
            } else {
                $model->setData($data);            
            
                try {
                    $model->save();
                    $this->messageManager->addSuccessMessage(__('You saved the Schedule.'));
                    $this->dataPersistor->clear('auraine_schedule_schedule');
            
                    if ($this->getRequest()->getParam('back')) {
                        return $resultRedirect->setPath('*/*/edit', ['schedule_id' => $model->getId()]);
                    }
                    return $resultRedirect->setPath('*/*/');
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                } catch (\Exception $e) {
                    $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Schedule.'));
                }
            }
        
            $this->dataPersistor->set('auraine_schedule_schedule', $data);
            return $resultRedirect->setPath('*/*/edit', ['schedule_id' => $this->getRequest()->getParam('schedule_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

