<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Staticcontent\Controller\Adminhtml\Content;

class Edit extends \Auraine\Staticcontent\Controller\Adminhtml\Content
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
        $id = $this->getRequest()->getParam('content_id');
        $model = $this->_objectManager->create(\Auraine\Staticcontent\Model\Content::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Content no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->coreRegistry->register('auraine_staticcontent_content', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $newcontent = 'New Content';
        $editcontent = 'Edit Content';
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __($editcontent) : __($newcontent),
            $id ? __($editcontent) : __($newcontent)
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Contents'));
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? __($editcontent.' %1', $model->getId()) : __($newcontent));
        return $resultPage;
    }
}
