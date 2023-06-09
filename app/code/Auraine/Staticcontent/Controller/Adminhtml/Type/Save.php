<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Staticcontent\Controller\Adminhtml\Type;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

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
        $returnPath = $resultRedirect->setPath('*/*/');
        if ($data) {
            $id = $this->getRequest()->getParam('type_id');
        
            $model = $this->_objectManager->create(\Auraine\Staticcontent\Model\Type::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Type no longer exists.'));
                $returnPath = $resultRedirect->setPath('*/*/');
            }
        
            $model->setData($data);
        
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Type.'));
                $this->dataPersistor->clear('auraine_staticcontent_type');
        
                if ($this->getRequest()->getParam('back')) {
                    $returnPath = $resultRedirect->setPath('*/*/edit', ['type_id' => $model->getId()]);
                }
                $returnPath = $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Type.'));
            }
        
            $this->dataPersistor->set('auraine_staticcontent_type', $data);
            $returnPath = $resultRedirect->setPath('*/*/edit', ['type_id' => $this->getRequest()->getParam('type_id')]);
        }
        return $returnPath;
    }
}
