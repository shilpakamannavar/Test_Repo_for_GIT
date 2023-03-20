<?php

namespace Alternativetechlab\Mobilelogin\Controller\Index;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\UrlFactory;
use Magento\Store\Model\StoreManagerInterface;
use Alternativetechlab\Mobilelogin\Helper\Data as AlternativetechlabHelper;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Registration as RegistrationModal;
use Magento\Customer\Model\CustomerExtractor;
use Magento\Framework\App\Action\Action;

/**
 * Class Registration
 * Alternativetechlab\Mobilelogin\Controller\Index
 */
class Registration extends Action implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     */
    protected $jsonResultFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var AlternativetechlabHelper
     */
    protected $helperData;
    protected $accountManagement;
    protected $customerUrl;
    protected $urlModel;

    /**
     * Registration constructor.
     * @param Context $context
     * @param JsonFactory $jsonResultFactory
     * @param StoreManagerInterface $storeManager
     * @param AlternativetechlabHelper $helperData
     * @param RegistrationModal $registration
     * @param CustomerExtractor $customerExtractor
     * @param Session $customerSession
     */
    public function __construct(
        Context                    $context,
        JsonFactory                $jsonResultFactory,
        StoreManagerInterface      $storeManager,
        AlternativetechlabHelper             $helperData,
        RegistrationModal          $registration,
        CustomerExtractor          $customerExtractor,
        Session                    $customerSession,
        AccountManagementInterface $accountManagement,
        CustomerUrl                $customerUrl,
        UrlFactory                 $urlFactory
    ) {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->storeManager = $storeManager;
        $this->helperData = $helperData;
        $this->session = $customerSession;
        $this->registration = $registration;
        $this->customerExtractor = $customerExtractor;
        $this->accountManagement = $accountManagement;
        $this->customerUrl = $customerUrl;
        $this->urlModel = $urlFactory->create();
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|
     * \Magento\Framework\Controller\Result\Json|
     * \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            if (!$this->getRequest()->isPost()) {
                $jsonOutput = ["status" => false, "message" => __("This operation is not permitted.")];
            } else {
                if ($this->session->isLoggedIn() || !$this->registration->isAllowed()) {
                    $jsonOutput = ["status" => false,
                        "message" => __("There is some issue. Please try after some time.")];
                } else {
                    $websiteId = $this->storeManager->getWebsite()->getId();
                    $data = $this->getRequest()->getParams();

                    $isVerified = $this->helperData->checkOTPisVerified(
                        $data,
                        $this->helperData::REGISTRATION_OTP_TYPE,
                        $websiteId
                    );

                    if (count($isVerified) == 1) {
                        $customer = $this->customerExtractor->extract('customer_account_create', $this->_request);
                        $customer->setAddresses([]);
                        ///$redirectUrl = $this->session->getBeforeAuthUrl();
                        $redirectUrl = $this->helperData->getAfterLoginRedirect();


                        $customerCollection = $this->helperData
                            ->checkCustomerExists($customer->getEmail(), "email", $websiteId);
                        if (count($customerCollection) > 0) {
                            $jsonOutput = ["status" => false,
                                "message" => __("Customer already registered with same EmailId")];
                            $jsonResult = $this->jsonResultFactory->create();
                            $jsonResult->setData($jsonOutput);
                            return $jsonResult;
                        }

                        $customer = $this->helperData->createAccount($customer, $data, $redirectUrl, $websiteId);
                        $this->_eventManager->dispatch(
                            'customer_register_success',
                            ['account_controller' => $this, 'customer' => $customer]
                        );
                        $confirmationStatus = $this->accountManagement->getConfirmationStatus($customer->getId());
                        if ($confirmationStatus === AccountManagementInterface::ACCOUNT_CONFIRMATION_REQUIRED) {
                            $this->messageManager->addComplexSuccessMessage(
                                'confirmAccountSuccessMessage',
                                [
                                    'url' => $this->customerUrl->getEmailConfirmationUrl($customer->getEmail()),
                                ]
                            );
                            $redirectUrl = $this->urlModel->getUrl('customer/account/login/', ['_secure' => true]);
                            $resultRedirect = $this->resultRedirectFactory->create();
                            $resultRedirect->setUrl($this->_redirect->success($redirectUrl));
                        } else {
                            $this->session->setCustomerDataAsLoggedIn($customer);
                        }
                    } else {
                        $jsonOutput = ["status" => false, "message" => __("Mobile no is not verified.")];
                    }

                    $jsonOutput = ["status" => true, "redirectUrl" => $redirectUrl];
                }
            }
        } catch (\Exception $e) {
            $jsonOutput = ["status" => false, "message" => __("There is some issue. Please try after some time.")];
        }

        $jsonResult = $this->jsonResultFactory->create();
        $jsonResult->setData($jsonOutput);
        return $jsonResult;
    }
}
