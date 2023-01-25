<?php
declare(strict_types=1);

namespace Auraine\ZipCode\Controller\Adminhtml\Pincode;

class Edit extends \Auraine\ZipCode\Controller\Adminhtml\Pincode
{

    /**
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
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
        $id = $this->getRequest()->getParam('pincode_id');
        $model = $this->_objectManager->create(\Auraine\ZipCode\Model\Pincode::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Pincode no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('auraine_zipcode_pincode', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Pincode') : __('New Pincode'),
            $id ? __('Edit Pincode') : __('New Pincode')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Pincodes'));
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? __('Edit Pincode %1', $model->getId()) : __('New Pincode')
        );
        return $resultPage;
    }
}
