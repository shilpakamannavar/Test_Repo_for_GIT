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
    protected $filter;
    /**@var CollectionFactory
     */
    protected $collectionFactory;
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

        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }
    /**
     * MassDelete function for brands list by selecting the ids
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());

            $done = 0;
            foreach ($collection as $item) {
                $item->delete();
                ++$done;
            }

            if ($done) {
                $this->messageManager->addSuccess(__('A total of %1 record(s) were modified.', $done));
            }
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
    /**
     * Check Category Map recode delete Permission. @return bool*/
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Auraine_Brands::brands_massdelete');
    }
}
