<?php
namespace Auraine\CsvUploader\Controller\Adminhtml\Csv;

class Upload extends \Magento\Backend\App\Action
{

    /**
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
  
    /**
     * Function Construct
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
  
    /**
     * Function Execute
     *
     * @return void
     */
    public function execute()
    {
      /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Auraine_CsvUploader::csv_uploader');
        $resultPage->getConfig()->getTitle()->prepend(__('Bulk Upload Size And Color Attribute'));
        return $resultPage;
    }

    /**Is allowes
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Auraine_CsvUploader::csv_upload');
    }
}
