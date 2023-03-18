<?php
namespace Alternativetechlab\Mobilelogin\Controller\Index;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Magento\Store\Model\StoreManagerInterface;
use Alternativetechlab\Mobilelogin\Helper\Data as AlternativetechlabHelper;
use Magento\Framework\App\Action\Action;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Api\AccountManagementInterface;

/**
 * Class Loginotpsend
 * Alternativetechlab\Mobilelogin\Controller\Index
 */
class Loginotpsend extends Action implements HttpPostActionInterface
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
    private $customerRepository;
    protected $accountManagement;

    /**
     * Loginotpsend constructor.
     * @param Context $context
     * @param JsonFactory $jsonResultFactory
     * @param StoreManagerInterface $storeManager
     * @param AlternativetechlabHelper $helperData
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonResultFactory,
        StoreManagerInterface $storeManager,
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $accountManagement,
        AlternativetechlabHelper $helperData
    ) {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->storeManager = $storeManager;
        $this->helperData = $helperData;
        $this->_customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->accountManagement = $accountManagement;
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

            $mobilenumber = $data['loginotpmob'];

            $customerCollection = $this->_customerFactory->create()->getCollection()
                ->addAttributeToSelect("*")
                ->addAttributeToFilter('mobilenumber', $mobilenumber)
                ->load();



            foreach ($customerCollection as $customerData) {
                $id = $customerData->getId();
            }

            if (isset($id)) {
                $check = $this->accountManagement->getConfirmationStatus($id);
                if ($check == 'account_confirmation_required') {
                    $jsonOutput = ["status"=>false,
                                    "message"=>__("This account isn't confirmed. Verify and try again.")];
                } else {
                    $jsonOutput = $this->helperData->sendLoginOTP($data, $websiteId);
                }
            } else {
                $jsonOutput = ["status"=>false, "message"=>__("Customer does not exist.")];
            }
        }

        $jsonResult = $this->jsonResultFactory->create();
        $jsonResult->setData($jsonOutput);
        return $jsonResult;
    }
}
