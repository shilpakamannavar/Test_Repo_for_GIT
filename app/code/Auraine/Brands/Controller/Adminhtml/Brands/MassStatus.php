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
    protected $_filter;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

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

        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        //print_r($this->getRequest()->getParam('status'));die;
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        //print_r(count($collection));die;
        $recordUpdated = 0;
        foreach ($collection as $record) {
            //print_r($record['id']);die;
            $record->setStatus($this->getRequest()->getParam('status'))->save();
            $recordUpdated++;
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been updated.', $recordUpdated));

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }

  
}
