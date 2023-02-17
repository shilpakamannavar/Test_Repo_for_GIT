<?php

namespace Auraine\Brands\Controller\Adminhtml\Brands;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Auraine\Brands\Model\ResourceModel\Brands\CollectionFactory;

class MassDelete extends \Magento\Backend\App\Action
{
    /** Massactions filter.
     * @var Filter
     */
    protected $_filter;
    /**@var CollectionFactory
     */
    protected $_collectionFactory;
    /** Adding context,filter and collectionFactory
     * @param Context           $context
     * @param Filter            $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {

        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context);
    }
    /**
     * MassDelete function for brands list by selecting the ids
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $recordDeleted = 0;
        foreach ($collection->getItems() as $record) {
            $record->setId($record->getEntityId());
            $record->delete();
            $recordDeleted++;
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $recordDeleted));

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }
    /**
     * Check Category Map recode delete Permission. @return bool*/
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Auraine_Brands::brands_massdelete');
    }
}
