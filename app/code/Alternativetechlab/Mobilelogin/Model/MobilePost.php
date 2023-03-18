<?php
namespace Alternativetechlab\Mobilelogin\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

use Magento\Integration\Model\Oauth\TokenFactory as TokenModelFactory;
use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;

use Alternativetechlab\Mobilelogin\Helper\Data as AlternativetechlabHelper;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\AuthenticationInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Customer as CustomerResource;


use Magento\Framework\Exception\AuthenticationException;
use Magento\Customer\Model\Data\Customer as CustomerData;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface as PsrLogger;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Framework\Encryption\EncryptorInterface as Encryptor;
use Magento\Backend\App\ConfigInterface;
use Magento\Framework\Stdlib\DateTime;
use Magento\Customer\Model\CustomerAuthUpdate;

/**
 * Class MobilePost
 * Alternativetechlab\Mobilelogin\Model
 */
class MobilePost implements \Alternativetechlab\Mobilelogin\Api\MobilePostInterface
{

    const LOCKOUT_THRESHOLD_PATH = 'customer/password/lockout_threshold';
    const MAX_FAILURES_PATH = 'customer/password/lockout_failures';

    const CUSTOMER_NOT_EXIST = "Customer does not exist.";

    const INVALID_LIST = "Invalid parameter list.";

    protected $customerRegistry;
    protected $encryptor;
    protected $backendConfig;
    protected $dateTime;

    private $customerAuthUpdate;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param TokenModelFactory $tokenModelFactory
     * @param AccountManagement $accountManagement
     * @param CustomerResource $customerResource
     * @param CollectionFactory $customerCollecction
     * @param AuthenticationInterface $customerAuthentication
     * @param AlternativetechlabHelper $helperData
     * @param CustomerFactory $customerFactory
     * @param Customer $customer
     * @param CustomerData $customerData
     * @param PsrLogger $logger
     * @param JsonHelper $jsonHelper
     * @param CustomerRegistry $customerRegistry
     * @param Encryptor $encryptor
     * @param ConfigInterface $backendConfig
     * @param DateTime $dateTime
     * @param CustomerAuthUpdate $customerAuthUpdate
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        TokenModelFactory $tokenModelFactory,
        AccountManagement $accountManagement,
        CustomerResource $customerResource,
        CollectionFactory $customerCollecction,
        AuthenticationInterface $customerAuthentication,
        AlternativetechlabHelper $helperData,
        CustomerFactory $customerFactory,
        Customer $customer,
        CustomerData $customerData,
        PsrLogger $logger,
        JsonHelper $jsonHelper,
        CustomerRegistry $customerRegistry,
        Encryptor $encryptor,
        ConfigInterface $backendConfig,
        DateTime $dateTime,
        CustomerAuthUpdate $customerAuthUpdate
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->customer    = $customer;
        $this->tokenModelFactory = $tokenModelFactory;
        $this->accountManagement = $accountManagement;
        $this->customerResource = $customerResource;
        $this->customerCollecction = $customerCollecction;
        $this->customerAuthentication = $customerAuthentication;
        $this->helperdata = $helperData;
        $this->_customerFactory = $customerFactory;
        $this->customerData    = $customerData;
        $this->logger = $logger;
        $this->jsonHelper = $jsonHelper;
        $this->customerRegistry = $customerRegistry;
        $this->encryptor = $encryptor;
        $this->backendConfig = $backendConfig;
        $this->dateTime = $dateTime;
        $this->customerAuthUpdate = $customerAuthUpdate;
    }

    /**
     * {@inheritdoc}
     */
    public function getLoginOTP($mobileNumber, $websiteId)
    {
        try {
            if (empty($mobileNumber) || (empty($websiteId) && empty($websiteId)>0)) {
                return [["status"=>false, "message"=>__(self::INVALID_LIST)]];
            }

            $response = $this->helperdata->sendLoginOTP(["loginotpmob"=>$mobileNumber], $websiteId);
            return [$response];
        } catch (\Exception $e) {
            throw new AuthenticationException(__($e->getMessage()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLoginOTPVerify($mobileNumber, $otp, $websiteId)
    {
        try {
            if (empty($mobileNumber) || empty($otp) || (empty($websiteId) && empty($websiteId)>0)) {
                return [["status"=>false, "message"=>__(self::INVALID_LIST)]];
            }

            $response = $this->helperdata->verifyLoginOTP(["mobile"=>$mobileNumber,"verifyotp"=>$otp], $websiteId);
            if ($response['status']) {
                $customerCollection = $this->helperdata->checkCustomerExists($mobileNumber, "mobile", $websiteId);
                if (count($customerCollection) > 0) {
                    $customer = $customerCollection->getFirstItem();
                    $token = $this->tokenModelFactory->create()->createCustomerToken($customer->getId())->getToken();
                    $response['token'] = $token;
                } else {
                    $response = ["status"=>false, "message"=>__("Customer does not exists.")];
                }
            }

            return [$response];
        } catch (\Exception $e) {
            throw new AuthenticationException(__($e->getMessage()));
        }
    }
    public function getApiUpdateOTPCode($newMobileNumber, $websiteId, $customerId, $oldMobileNumber)
    {
        try {
            if (empty($newMobileNumber) || (empty($websiteId) && empty($websiteId)>0) ||
                empty($customerId) || empty($oldMobileNumber)) {
                return [["status"=>false, "message"=>__(self::INVALID_LIST)]];
            }

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $customerObj = $objectManager->create('Magento\Customer\Model\ResourceModel\Customer\Collection');
            $collection = $customerObj->addAttributeToSelect('*')
                ->addAttributeToFilter('mobilenumber', $oldMobileNumber)
                ->addAttributeToFilter('entity_id', $customerId)
                ->load();

            if (count($collection) > 0) {
                $response = $this->helperdata->sendUpdateOTPCode($newMobileNumber, $websiteId);
            } else {
                return [["status"=>false, "message"=>__(self::CUSTOMER_NOT_EXIST)]];
            }

            return [$response];
        } catch (\Exception $e) {
            throw new AuthenticationException(__($e->getMessage()));
        }
    }

    public function updateNumberVerifyOTP($newMobileNumber, $otp, $customerId, $oldMobileNumber, $websiteId)
    {
        try {
            if (empty($newMobileNumber) || (empty($websiteId) && empty($websiteId)>0) || empty($customerId)
                || empty($oldMobileNumber) || empty($otp)) {
                return [["status"=>false, "message"=>__(self::INVALID_LIST)]];
            }

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $customerObj = $objectManager->create('Magento\Customer\Model\ResourceModel\Customer\Collection');
            $collection = $customerObj->addAttributeToSelect('*')
                ->addAttributeToFilter('mobilenumber', $oldMobileNumber)
                ->addAttributeToFilter('entity_id', $customerId)
                ->load();

            if (count($collection) > 0) {
                $response = $this->helperdata->verifyRegistrationOTP(
                    ["mobile"=>$newMobileNumber, "verifyotp"=>$otp],
                    $websiteId
                );
                if ($response['status'] == 1) {
                    $this->customerData->setId($customerId);
                    $this->customerData->setCustomAttribute('mobilenumber', $newMobileNumber);
                    $this->customer->updateData($this->customerData);
                    $this->customerResource->saveAttribute($this->customer, 'mobilenumber');
                    $jsonOutput = ["status" => true, "message" => __("Mobile number updated successfully.")];
                    return [$jsonOutput];
                }
            } else {
                $response = [["status"=>false, "message"=>__(self::CUSTOMER_NOT_EXIST)]];
            }

            return [$response];
        } catch (\Exception $e) {
            throw new AuthenticationException(__($e->getMessage()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLogin($mobileEmail, $password, $websiteId)
    {
        try {
            if (empty($mobileEmail) || empty($password) || empty($websiteId)) {
                return [["status"=>false, "message"=>__(self::INVALID_LIST)]];
            }

            $response = [];

            if (is_numeric($mobileEmail)) {
                $collection = $this->helperdata->checkCustomerExists($mobileEmail, "mobile", $websiteId);
            } else {
                $collection = $this->helperdata->checkCustomerExists($mobileEmail, "email", $websiteId);
            }

            if (count($collection) == 0) {
                return [["status"=>false, "message"=>__(self::CUSTOMER_NOT_EXIST)]];
            } else {
                $customerItem = $collection->getFirstItem();
                $customerId = $customerItem->getId();
            }

            if ($this->authenticate($customerId, $password)) {
                $token = $this->tokenModelFactory->create()->createCustomerToken($customerId)->getToken();
                $response['status'] = true;
                $response['token'] = $token;
            } else {
                $response = ["status"=>false, "message"=>__("Customer does not exists.")];
            }

            return [$response];
        } catch (\Exception $e) {
            throw new AuthenticationException(__($e->getMessage()));
        }
    }

    public function authenticate($customerId, $password)
    {
        $customerSecure = $this->customerRegistry->retrieveSecureData($customerId);
        $hash = $customerSecure->getPasswordHash() ?? '';
        if (!$this->encryptor->validateHash($password, $hash)) {
            $this->processAuthenticationFailure($customerId);
            if ($this->isLocked($customerId)) {
                throw new LocalizedException(__('The account is locked.'));
            }

            throw new InvalidEmailOrPasswordException(__('Invalid login or password.'));
        }

        return true;
    }

    protected function getLockThreshold()
    {
        return $this->backendConfig->getValue(self::LOCKOUT_THRESHOLD_PATH) * 60;
    }

    protected function getMaxFailures()
    {
        return $this->backendConfig->getValue(self::MAX_FAILURES_PATH);
    }

    public function processAuthenticationFailure($customerId)
    {
        $now = new \DateTime();
        $lockThreshold = $this->getLockThreshold();
        $maxFailures =  $this->getMaxFailures();
        $customerSecure = $this->customerRegistry->retrieveSecureData($customerId);

        if (!($lockThreshold && $maxFailures)) {
            return;
        }

        $failuresNum = (int)$customerSecure->getFailuresNum() + 1;

        $firstFailureDate = $customerSecure->getFirstFailure();
        if ($firstFailureDate) {
            $firstFailureDate = new \DateTime($firstFailureDate);
        }

        $lockThreshInterval = new \DateInterval('PT' . $lockThreshold . 'S');
        $lockExpires = $customerSecure->getLockExpires();
        $lockExpired = ($lockExpires !== null) && ($now > new \DateTime($lockExpires));
        // set first failure date when this is the first failure or the lock is expired
        if (1 === $failuresNum || !$firstFailureDate || $lockExpired) {
            $customerSecure->setFirstFailure($this->dateTime->formatDate($now));
            $failuresNum = 1;
            $customerSecure->setLockExpires(null);
            // otherwise lock customer
        } elseif ($failuresNum >= $maxFailures) {
            $customerSecure->setLockExpires($this->dateTime->formatDate($now->add($lockThreshInterval)));
        }

        $customerSecure->setFailuresNum($failuresNum);
        $this->customerAuthUpdate->saveAuth($customerId);
    }

    public function isLocked($customerId)
    {
        $currentCustomer = $this->customerRegistry->retrieve($customerId);
        return $currentCustomer->isCustomerLocked();
    }

    /**
     * {@inheritdoc}
     */
    public function getForgotPasswordOTP($mobileNumber, $websiteId)
    {
        try {
            if (empty($mobileNumber) || (empty($websiteId) && empty($websiteId)>0)) {
                return [["status"=>false, "message"=>__(self::INVALID_LIST)]];
            }

            $response = $this->helperdata->sendForgotPasswordOTP(["forgotmob"=>$mobileNumber], $websiteId);
            return [$response];
        } catch (\Exception $e) {
            throw new AuthenticationException(__($e->getMessage()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getForgotPasswordOTPVerify($mobileNumber, $otp, $websiteId)
    {
        try {
            if (empty($mobileNumber) || empty($otp) || (empty($websiteId) && empty($websiteId)>0)) {
                return [["status"=>false, "message"=>__(self::INVALID_LIST)]];
            }

            $response = $this->helperdata->verifyForgotPasswordOTP(
                ["mobile"=>$mobileNumber,"verifyotp"=>$otp],
                $websiteId
            );
            return [$response];
        } catch (\Exception $e) {
            throw new AuthenticationException(__($e->getMessage()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resetPassword($mobileNumber, $otp, $password, $websiteId)
    {
        try {
            if (empty($mobileNumber) || empty($otp) || empty($password) || (empty($websiteId) && empty($websiteId)>0)) {
                return [["status"=>false, "message"=>__(self::INVALID_LIST)]];
            }

            $isVerified = $this->helperdata->checkOTPisVerified(
                [
                    "mobile"=>$mobileNumber,
                    "otp"=>$otp,
                ],
                $this->helperdata::FORGOTPASSWORD_OTP_TYPE,
                $websiteId
            );
            if (count($isVerified) == 1) {
                $response = $this->helperdata->resetForgotPassword(
                    ["mobilenumber"=>$mobileNumber,"password"=>$password],
                    $websiteId
                );
            } else {
                $response = ["status"=>false, "message"=>__("Mobile no is not verified.")];
            }

            return [$response];
        } catch (\Exception $e) {
            throw new AuthenticationException(__($e->getMessage()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createAccountOTP($mobileNumber, $websiteId)
    {
        try {
            if (empty($mobileNumber) || (empty($websiteId) && empty($websiteId)>0)) {
                return [["status"=>false, "message"=>__(self::INVALID_LIST)]];
            }

            $response = $this->helperdata->sendRegistrationOTP(["mobile"=>$mobileNumber], $websiteId);
            return [$response];
        } catch (\Exception $e) {
            throw new AuthenticationException(__($e->getMessage()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createAccountVerifyOTP($mobileNumber, $otp, $websiteId)
    {
        try {
            if (empty($mobileNumber) || empty($otp) || (empty($websiteId) && empty($websiteId)>0)) {
                return [["status"=>false, "message"=>__(self::INVALID_LIST)]];
            }

            $response = $this->helperdata->verifyRegistrationOTP(
                ["mobile"=>$mobileNumber, "verifyotp"=>$otp],
                $websiteId
            );
            return [$response];
        } catch (\Exception $e) {
            throw new AuthenticationException(__($e->getMessage()));
        }
    }

    public function createAccount(CustomerInterface $customer, $mobileNumber, $otp, $password = null, $redirectUrl = '')
    {
        try {
            $isVerified = $this->helperdata->checkOTPisVerified(
                [
                    "mobile"=>$mobileNumber,
                    "otp"=>$otp,
                ],
                $this->helperdata::REGISTRATION_OTP_TYPE,
                $customer->getWebsiteId()
            );
            if (count($isVerified) == 1) {
                $collection = $this->helperdata->checkCustomerExists(
                    $mobileNumber,
                    "mobile",
                    $customer->getWebsiteId()
                );
                if (count($collection) == 0) {
                    $customer->setCustomAttribute("mobilenumber", $mobileNumber);
                    $this->helperdata->createAccount(
                        $customer,
                        ["password"=>$password],
                        "",
                        $customer->getWebsiteId()
                    );
                    $response = ["status"=>true, "message"=>__("Customer account created.")];
                } else {
                    $response = ["status"=>false, "message"=>__("Customer is already exists.")];
                }
            } else {
                $response = ["status"=>false, "message"=>__("Mobile no is not verified.")];
            }

            return [$response];
        } catch (\Exception $e) {
            throw new AuthenticationException(__($e->getMessage()));
        }
    }
}
