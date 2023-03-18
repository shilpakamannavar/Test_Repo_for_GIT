<?php
namespace Alternativetechlab\Mobilelogin\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\ObjectManager;

class Apicall extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_SMSGATEWAY ='mobilelogin/smsgatways/gateway';

    protected $smsgatewaylist;
    protected $storeManager;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $smsgatewaylist = []
    ) {
        $this->smsgatewaylist = $smsgatewaylist;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getStoreid()
    {
        return $this->storeManager->getStore()->getId();
    }

    public function getSmsgatewaylist()
    {
        return $this->smsgatewaylist;
    }

    public function getSelectedGateway()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMSGATEWAY,
            ScopeInterface::SCOPE_STORE,
            $this->getStoreid()
        );
    }

    public function getSelectedGatewayModel()
    {
        if ($this->getSelectedGateway() != '' || $this->getSelectedGateway() != null) {
            $selectedGateway = $this->smsgatewaylist[$this->getSelectedGateway()];
            return ObjectManager::getInstance()->create($selectedGateway);
        } else {
            return null;
        }
    }

    public function callApiUrl($message, $mobilenumbers, $dlt)
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/apiCall.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info('Step1');
        $curentsmsModel = $this->getSelectedGatewayModel();

        if ($curentsmsModel == '' || $curentsmsModel == null) {
            return __("SMS Gateway haven't configured yet.");
        }

        if (!$curentsmsModel->validateSmsConfig()) {
            return __("Please, Make Sure You have Configured SMS Gateway Properly.");
        }

        return $curentsmsModel->callApiUrl($message, $mobilenumbers, $dlt);
    }
}
