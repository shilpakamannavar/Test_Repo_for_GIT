<?php

namespace Magecomp\Mobilelogin\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magecomp\Mobilelogin\Helper\Data as MagecompHelper;
use Magento\Framework\Controller\ResultFactory;

class Sentotpbyupdate extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
    protected $jsonResultFactory;
    protected $session;
    protected $formKeyValidator;
    public $_storeManager;
    protected $_messageManager;
    public $_helperdata;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $jsonResultFactory,
        Session $customerSession,
        AccountRedirect $accountRedirect,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        MagecompHelper $helperData
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->session = $customerSession;
        $this->accountRedirect = $accountRedirect;
        $this->_storeManager = $storeManager;
        $this->_helperdata = $helperData;
        $this->_messageManager = $messageManager;
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

        $websiteId = $this->_storeManager->getWebsite()->getId();
        $data = $this->getRequest()->getParams();
        $returnVal = $this->_helperdata->sendUpdateOTPCode($data['mobile'], $websiteId);
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($returnVal);
        return $resultJson;
    }
}
