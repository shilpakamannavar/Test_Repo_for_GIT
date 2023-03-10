<?php

namespace Auraine\Brands\Controller\Adminhtml\Brands;

use Magento\Framework\Controller\ResultFactory;

class AddRow extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;
    /**
     * @var gridFactory
     */
    private $gridFactory;
    /**
     * @param construct $context
     *
     * @param coreRegistry $coreRegistry
     *
     * @param gridFactory $gridFactory
     *
     * @return string $construct
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Auraine\Brands\Model\GridFactory $gridFactory
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->gridFactory = $gridFactory;
    }
    /**
     * Mapped Grid List page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $rowId = (int) $this->getRequest()->getParam('id');
        $rowData = $this->gridFactory->create();
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        if ($rowId) {
            $rowData = $rowData->load($rowId);
            $rowTitle = $rowData->getTitle();
            if (!$rowData->getId()) {
                $this->messageManager->addError(__('Product Content not exist.'));
                $this->_redirect('brands/brands/rowdata');
                return;
            }
        }
        $this->coreRegistry->register('row_data', $rowData);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Auraine_Brands::manager');
        $title = $rowId ? __('Edit Brand ')." - ".$rowTitle." " : __('Add New Brand');
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }
    /**
     * Is allowed
     *
     * @return authorizationisallowed
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Auraine_Brands::brands_addrow');
    }
}
