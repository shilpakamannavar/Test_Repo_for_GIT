<?php
namespace Alternativetechlab\Mobilelogin\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Store\Model\StoreManagerInterface;
use Alternativetechlab\Mobilelogin\Helper\Data as AlternativetechlabHelper;
use Magento\Customer\Model\Session;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\HTTP\Header as HTTPHeader;

class Login extends Action implements HttpPostActionInterface
{
    const INVALID_LOGIN = "Invalid login or password.";
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
     * @var
     */
    private $cookieMetadataManager;

    /**
     * @var
     */
    private $scopeConfig;

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * @var HTTPHeader
     */
    private $httpHeader;

    /**
     * Login constructor.
     * @param Context $context
     * @param JsonFactory $jsonResultFactory
     * @param StoreManagerInterface $storeManager
     * @param AlternativetechlabHelper $helperData
     * @param Session $customerSession
     * @param Validator $formKeyValidator
     * @param AccountManagementInterface $customerAccountManagement
     * @param AccountRedirect $accountRedirect
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param CollectionFactory $customerCollectionFactory
     * @param RemoteAddress $remoteAddress
     * @param HTTPHeader $httpHeader
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonResultFactory,
        StoreManagerInterface $storeManager,
        AlternativetechlabHelper $helperData,
        Session $customerSession,
        Validator $formKeyValidator,
        AccountManagementInterface $customerAccountManagement,
        AccountRedirect $accountRedirect,
        CookieMetadataFactory $cookieMetadataFactory,
        CollectionFactory $customerCollectionFactory,
        RemoteAddress $remoteAddress,
        HTTPHeader $httpHeader
    ) {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->storeManager = $storeManager;
        $this->helperData = $helperData;
        $this->session = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->accountRedirect = $accountRedirect;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->remoteAddress = $remoteAddress;
        $this->httpHeader = $httpHeader;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    private function getCookieManager()
    {
        if (!$this->cookieMetadataManager) {
            $this->cookieMetadataManager = \Magento\Framework\App\ObjectManager::getInstance()->
            get(
                \Magento\Framework\Stdlib\Cookie\PhpCookieManager::class
            );
        }

        return $this->cookieMetadataManager;
    }

    /**
     * @return CookieMetadataFactory|mixed
     */
    private function getCookieMetadataFactory()
    {
        if (!$this->cookieMetadataFactory) {
            $this->cookieMetadataFactory = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory::class
            );
        }

        return $this->cookieMetadataFactory;
    }

    /**
     * @return \Magento\Framework\App\Config\ScopeConfigInterface|mixed
     */
    private function getScopeConfig()
    {
        if (!($this->scopeConfig instanceof \Magento\Framework\App\Config\ScopeConfigInterface)) {
            return \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\App\Config\ScopeConfigInterface::class
            );
        } else {
            return $this->scopeConfig;
        }
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|
     * \Magento\Framework\Controller\Result\Json|
     * \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->getRequest()->isPost()) {
            $jsonOutput = ["status"=>false, "message"=>__("This operation is not permitted.")];
        } else {
            try {
                if ($this->session->isLoggedIn() || !$this->formKeyValidator->validate($this->getRequest())) {
                    $jsonOutput = ["status"=>false, "message"=>__("There is some issue. Please try after some time.")];
                } else {
                    $login = $this->getRequest()->getPost('login');
                    if (!$this->validate($login['username'])) {
                        throw new \InvalidArgumentException(__(self::INVALID_LOGIN));
                    }

                    if (empty($login['username']) || empty($login['password'])) {
                        $jsonOutput = [
                            "status"=>false,
                            "message"=>__("Username and/or passoword should not be empty.")
                        ];
                    } else {
                        $jsonOutput = $this->getCustomerLogin($login);
                    }
                }
            } catch (\EmailNotConfirmedException $e) {
                $value = $this->customerUrl->getEmailConfirmationUrl($login['username']);
                $jsonOutput = ["status"=>false, "message"=>__(
                    'This account is not confirmed. <a href="%1">Click here</a> to resend confirmation email.',
                    $value
                )];
            } catch (\UserLockedException $e) {
                $jsonOutput = ["status"=>false, "message"=>__(
                    'The account is locked. Please wait and try again or contact %1.',
                    $this->getScopeConfig()->getValue('contact/email/recipient_email')
                )];
            } catch (\AuthenticationException $e) {
                $jsonOutput = ["status"=>false, "message"=>__('Invalid login or password.')];
            } catch (\LocalizedException $e) {
                $jsonOutput = ["status"=>false, "message"=>$e->getMessage()];
            } catch (\Exception $e) {
                $jsonOutput = ["status"=>false, "message"=>$e->getMessage()];
            }
        }

        $jsonResult = $this->jsonResultFactory->create();
        $jsonResult->setData($jsonOutput);
        return $jsonResult;
    }

    public function validate($username)
    {
        $pattern = '/^9\d{11}$/';
        $emailPattern = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';

        if (!is_numeric($username)) {
            return (preg_match($emailPattern, $username)) ? true : false;
        }

        if (preg_match($pattern, $username)) {
            return (strlen($username) >= 10 && strlen($username) == 12) ? true : false;
        } else {
            return false;
        }
    }

    /**
     * Check customer login
     *
     * @param array $login
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomerLogin($login = [])
    {
        if (is_numeric($login['username'])) {
            $collection = $this->customerCollectionFactory->create()
                ->addAttributeToFilter('mobilenumber', $login['username'])
                ->load();
            if (count($collection) == 0) {
                return ["status"=>false, "message"=>__(self::INVALID_LOGIN)];
            } else {
                $customer = $collection->getFirstItem();
                $login['username'] = $customer->getEmail();
            }
        }

        if (!empty($login['username']) && !empty($login['password'])) {
            if ($this->helperData->isEnableLoginEmail()) {
                $this->helperData->sendMail(
                    $this->remoteAddress->getRemoteAddress(),
                    $login['username'],
                    $this->httpHeader->getHttpUserAgent()
                );
            }

            $customer = $this->customerAccountManagement->authenticate(
                $login['username'],
                $login['password']
            );
            $this->session->setCustomerDataAsLoggedIn($customer);
            $this->session->regenerateId();

            if ($this->getCookieManager()->getCookie('mage-cache-sessid')) {
                $metadata = $this->getCookieMetadataFactory()->createCookieMetadata();
                $metadata->setPath('/');
                $this->getCookieManager()->deleteCookie('mage-cache-sessid', $metadata);
            }

            $redirectUrl = $this->accountRedirect->getRedirectCookie();
            $dashboard = $this->getScopeConfig()->getValue('customer/startup/redirect_dashboard');
            if (!$dashboard && $redirectUrl) {
                $this->accountRedirect->clearRedirectCookie();
            }

            $jsonOutput = ["status"=>true, "redirectUrl"=>$redirectUrl];
        } else {
            $jsonOutput = [
                "status"=>false,
                "message"=>__("Username and/or passoword should not be empty.")
            ];
        }

        return $jsonOutput;
    }
}
