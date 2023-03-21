<?php

namespace Alternativetechlab\Mobilelogin\Plugin;

use Alternativetechlab\Mobilelogin\Model\OtpFactory;
use Alternativetechlab\Mobilelogin\Helper\Data as AlternativetechlabHelper;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\UrlFactory;

class Pluginconfirmregistration
{
    protected $messageManager;
    protected $session;
    protected $urlManager;
    protected $redirect;
    protected $modelRegOtpFactory;
    protected $responseFactory;
    protected $request;
    protected $helperData;
    protected $urlModel;
    protected $resultRedirectFactory;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface        $messageManager,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Framework\UrlInterface                    $urlManager,
        \Magento\Framework\App\Response\RedirectInterface  $redirect,
        \Alternativetechlab\Mobilelogin\Model\OtpFactory             $modelRegOtpFactory,
        AlternativetechlabHelper                                     $helperData,
        \Magento\Framework\App\RequestInterface            $request,
        \Magento\Framework\App\ResponseFactory             $responseFactory,
        UrlFactory       $urlFactory,
        RedirectFactory  $redirectFactory
    ) {
        $this->resultRedirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->session = $session;
        $this->urlManager = $urlManager;
        $this->helperData = $helperData;
        $this->redirect = $redirect;
        $this->modelRegOtpFactory = $modelRegOtpFactory;
        $this->responseFactory = $responseFactory;
        $this->request = $request;
        $this->urlModel = $urlFactory->create();
    }

    public function aroundExecute(
        \Magento\Customer\Controller\Account\CreatePost $subject,
        \Closure                                        $proceed
    ) {
        if ($this->helperData->isEnable()) {
            try {
                    $postdata = $this->request->getParams();
                    $finalnumber = $postdata['mobilenumber'];

                    $otpModels = $this->modelRegOtpFactory->create();
                    $collection = $otpModels->getCollection()
                        ->addFieldToFilter('mobile', $finalnumber)->getFirstItem();
                if ($collection->getData('is_verify') == '1') {
                    return $proceed();
                } else {
                    $this->messageManager->addError(
                        'Invalid OTP'
                    );
                    $defaultUrl = $this->urlModel->getUrl('*/*/create', ['_secure' => true]);
                    $resultRedirect = $this->resultRedirectFactory->create();
                    return $resultRedirect->setUrl($defaultUrl);
                }
            } catch (\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
            }
        }

        return $proceed();
    }
}
