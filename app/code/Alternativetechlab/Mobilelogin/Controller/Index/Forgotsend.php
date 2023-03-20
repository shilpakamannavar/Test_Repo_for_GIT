<?php
namespace Alternativetechlab\Mobilelogin\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Store\Model\StoreManagerInterface;
use Alternativetechlab\Mobilelogin\Helper\Data as AlternativetechlabHelper;
use Magento\Framework\App\Action\Action;

/**
 * Class Forgotsend
 * Alternativetechlab\Mobilelogin\Controller\Index
 */
class Forgotsend extends Action implements HttpPostActionInterface
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

    /**
     * Forgotsend constructor.
     * @param Context $context
     * @param JsonFactory $jsonResultFactory
     * @param StoreManagerInterface $storeManager
     * @param AlternativetechlabHelper $helperData
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonResultFactory,
        StoreManagerInterface $storeManager,
        AlternativetechlabHelper $helperData
    ) {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->storeManager = $storeManager;
        $this->helperData = $helperData;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|
     * \Magento\Framework\Controller\Result\Json|
     * \Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        if (!$this->getRequest()->isPost()) {
            $jsonOutput = ["status"=>false, "message"=>__("This operation is not permitted.")];
        } else {
            $websiteId = $this->storeManager->getWebsite()->getId();
            $data = $this->getRequest()->getParams();
            if (isset($data['otp_reset_type']) && $data['otp_reset_type'] == "mobile") {
                if ($data['forgotmob']) {
                    $jsonOutput = $this->helperData->sendForgotPasswordOTP($data, $websiteId);
                } else {
                    $jsonOutput = ["status"=>false, "message"=>__("Please enter correct mobile no.")];
                }
            } elseif (isset($data['otp_reset_type']) && $data['otp_reset_type'] == "email") {
                if ($data['email']) {
                    $jsonOutput = $this->helperData->sendForgotPasswordEmail($data, $websiteId);
                } else {
                    $jsonOutput = ["status"=>false, "message"=>__("--Please enter correct email address.")];
                }
            } else {
                $jsonOutput = ["status"=>false, "message"=>__("This operation is not permitted.")];
            }
        }

        $jsonResult = $this->jsonResultFactory->create();
        $jsonResult->setData($jsonOutput);
        return $jsonResult;
    }
}