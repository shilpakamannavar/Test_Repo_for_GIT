<?php
namespace Alternativetechlab\Mobilelogin\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Action;
use Alternativetechlab\Mobilelogin\Helper\Data;

/**
 * Class Updatemobile
 * Alternativetechlab\Mobilelogin\Controller\Index
 */
class Updatemobile extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Data
     */
     protected $helper;

    /**
     * Updatemobile constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Data $helper
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|
     * \Magento\Framework\Controller\ResultInterface|
     * \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if ($this->helper->isEnable()) {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__('Mobile Number Update'));

            return $resultPage;
        } else {
            $this->_redirect('customer/account/login');
        }
    }
}
