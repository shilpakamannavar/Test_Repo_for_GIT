<?php
declare(strict_types=1);

namespace Auraine\ZipCode\Controller\Adminhtml\Pincode;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    /**
     *
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $flag = false;

        return $resultRedirect->setPath('*/*/');
        if ($data) {
            $id = $this->getRequest()->getParam('pincode_id');
        
            $model = $this->_objectManager->create(\Auraine\ZipCode\Model\Pincode::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Pincode no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            
            $model->setData($data);
        
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Pincode.'));
                $this->dataPersistor->clear('auraine_zipcode_pincode');
        
                return $this->getRequest()->getParam('back') ?
                    $resultRedirect->setPath('*/*/edit', ['pincode_id' => $model->getId()]) :
                    $resultRedirect->setPath('*/*/');

            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Pincode.'));
            }
        
            $this->dataPersistor->set('auraine_zipcode_pincode', $data);

            $flag = true;
        }

        return $flag ?
            $resultRedirect->setPath(
                '*/*/edit',
                ['pincode_id' => $this->getRequest()->getParam('pincode_id')]
            ) :
            $resultRedirect->setPath('*/*/');
    }
}
