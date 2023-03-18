<?php

namespace Alternativetechlab\Mobilelogin\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Store\Model\StoreManagerInterface;
use Alternativetechlab\Mobilelogin\Helper\Data as AlternativetechlabHelper;
use Magento\Framework\Controller\ResultFactory;

class Sentotpbyupdate extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $jsonResultFactory;
    protected $session;
    protected $formKeyValidator;
    public $storeManager;
    protected $messageManager;
    public $helperdata;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $jsonResultFactory,
        Session $customerSession,
        AccountRedirect $accountRedirect,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        AlternativetechlabHelper $helperData
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->session = $customerSession;
        $this->accountRedirect = $accountRedirect;
        $this->storeManager = $storeManager;
        $this->helperdata = $helperData;
        $this->messageManager = $messageManager;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();
        if ($data['mobile'] == $this->session->getCustomer()->getMobilenumber()) {
            $returnVal = [__("Your Mobile Number is Already Verified.")];
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData($returnVal);
            return $resultJson;
        }

        $websiteId = $this->storeManager->getWebsite()->getId();
        $data = $this->getRequest()->getParams();
        $returnVal = $this->helperdata->sendUpdateOTPCode($data['mobile'], $websiteId);
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($returnVal);
        return $resultJson;
    }
}
