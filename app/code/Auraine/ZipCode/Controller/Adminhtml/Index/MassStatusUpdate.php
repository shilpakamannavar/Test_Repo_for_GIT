<?php
namespace Auraine\ZipCode\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Auraine\Zipcode\Model\ResourceModel\Pincode\CollectionFactory;

class MassStatusUpdate extends \Magento\Backend\App\Action
{
    /**
     * Massactions filter.
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context           $context
     * @param Filter            $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {

        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Controller method for mass status update.
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        
        $resultRedirect = $this->resultRedirectFactory->create();
        
        $recordUpdated = 0;
        foreach ($collection as $record) {
        
            $record->setStatus($this->getRequest()->getParam('status'))->save();
            $recordUpdated++;
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been updated.', $recordUpdated));

        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
}
