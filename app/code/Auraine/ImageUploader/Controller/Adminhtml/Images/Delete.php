<?php
namespace Auraine\ImageUploader\Controller\Adminhtml\Images;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Auraine\ImageUploader\Model\ResourceModel\Image\CollectionFactory ;

class Delete extends Action
{
    public $imageFactory;
    
    public function __construct(
        Context $context,
        CollectionFactory $imageFactory
    ) {
        $this->imageFactory = $imageFactory;
        parent::__construct($context);
    }

    /**
     * function
     *
     * @return void
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('id');
        try {
            $imageModel = $this->imageFactory->create();
            $imageModel->load($id);
            /// also unlink image
            $imageModel->delete();
            $this->messageManager->addSuccessMessage(__('You deleted the image.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $resultRedirect->setPath('*/*/');
    }
}
