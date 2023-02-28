<?php

/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Auraine\Staticcontent\Controller\Adminhtml\Type;

class Edit extends \Auraine\Staticcontent\Controller\Adminhtml\Type

{

    protected $resultPageFactory;

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
        $id = $this->getRequest()->getParam('type_id');
        $model = $this->_objectManager->create(\Auraine\Staticcontent\Model\Type::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Type no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->coreRegistry->register('auraine_staticcontent_type', $model);

        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $newtype = 'New Type';
        $edittype = 'Edit Type';
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __($edittype) : __($newtype),
            $id ? __($edittype) : __($newtype)
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Types'));
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? __($edittype.'%1', $model->getId()) : __($newtype));
        return $resultPage;
    }
}
