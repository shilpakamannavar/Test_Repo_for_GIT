<?php
namespace Auraine\ImageUploader\Controller\Adminhtml\Images;

class Index extends \Magento\Backend\App\Action
{

    /**
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
  
    /**
   *  Function
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
     * Execute
     *
     * @return void
     */
    public function execute()
    {
      /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Auraine_ImageUploader::images_uploader');
        $resultPage->getConfig()->getTitle()->prepend(__('Images'));
        return $resultPage;
    }
}
