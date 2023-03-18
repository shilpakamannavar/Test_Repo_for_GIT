<?php
namespace Alternativetechlab\Mobilelogin\Plugin;

use Alternativetechlab\Mobilelogin\Helper\Data as AlternativetechlabHelper;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\RequestInterface;

class Checkattribute
{
    protected $helperData;
    protected $request;

    public function __construct(
        ResultFactory $redirect,
        ManagerInterface $messageManager,
        AlternativetechlabHelper   $helperData,
        RequestInterface $request
    ) {
        $this->resultFactory = $redirect;
        $this->helperData = $helperData;
        $this->messageManager = $messageManager;
        $this->getRequest = $request;
    }

    public function aroundexecute(
        \Magento\Customer\Controller\Adminhtml\Index\Save $subject,
        $proceed,
        $data = "null",
        $requestInfo = false
    ) {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $dataVal = $subject->getRequest()->getPostValue();
        if (isset($dataVal['customer']) || $dataVal['customer'] != '') {
                $customerCollection = $this->helperData->checkCustomerExists(
                    $dataVal['customer']['mobilenumber'],
                    "mobile",
                    1
                );
            if (count($customerCollection) > 0) {
                foreach ($customerCollection as $customer) {
                    if ($customer->getId()!=$dataVal['customer']['entity_id']) {
                        $this->messageManager->addError(__('Mobile No Has Already Exist'));
                        return $resultRedirect->setRefererOrBaseUrl();
                    }
                }
            }
        }

        return $proceed();
    }
}
