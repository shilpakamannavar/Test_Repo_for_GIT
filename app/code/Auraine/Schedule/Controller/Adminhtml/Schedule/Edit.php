<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Schedule\Controller\Adminhtml\Schedule;

class Edit extends \Auraine\Schedule\Controller\Adminhtml\Schedule
{

    protected $resultPageFactory;

    /**
     * This should be set explicitly
     */
    public const  NEW_SCHEDULE = "New Schedule";

    /**
     * This should be set explicitly
     */
    public const  EDIT_SCHEDULE = "Edit Schedule";

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('schedule_id');
        $model = $this->_objectManager->create(\Auraine\Schedule\Model\Schedule::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Schedule no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('auraine_schedule_schedule', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __(self::EDIT_SCHEDULE) : __(self::NEW_SCHEDULE),
            $id ? __(self::EDIT_SCHEDULE) : __(self::NEW_SCHEDULE)
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Schedules'));
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? __(self::EDIT_SCHEDULE.' %1', $model->getId()) : __(self::NEW_SCHEDULE)
        );
        return $resultPage;
    }

    /**Is allowes
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Auraine_Schedule::schedule_edit');
    }
}

