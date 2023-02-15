<?php

declare(strict_types=1);

namespace Auraine\PushNotification\Controller\Adminhtml\Template;

class Delete extends \Auraine\PushNotification\Controller\Adminhtml\Template
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('template_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Auraine\PushNotification\Model\Template::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Template.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['template_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Template to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

