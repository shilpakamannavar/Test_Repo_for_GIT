<?php

namespace Auraine\Brands\Controller\Adminhtml\Brands;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Auraine\Brands\Model\ResourceModel\Brands\CollectionFactory;

class MassStatus extends \Magento\Backend\App\Action
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
     * Execute function
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $recordUpdated = 0;
        foreach ($collection as $record) {
            $record->setStatus($this->getRequest()->getParam('status'))->save();
            $recordUpdated++;
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been updated.', $recordUpdated));
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }
    /**
     * Check Category Map recode delete Permission. @return bool*/
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Auraine_Brands::brands_massstatus');
    }
}
