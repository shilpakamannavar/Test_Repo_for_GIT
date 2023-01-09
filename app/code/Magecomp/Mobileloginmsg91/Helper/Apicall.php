<?php
namespace Magecomp\Mobileloginmsg91\Helper;

class Apicall extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_MSG91_API_SENDERID = 'mobilelogin/smsgatways/msg91senderid';
    const XML_MSG91_API_AUTHKEY = 'mobilelogin/smsgatways/msg91authkey';
    const XML_MSG91_API_URL = 'mobilelogin/smsgatways/msg91apiurl';
    const XML_MSG91_API_ROUTER = 'mobilelogin/smsgatways/msg91route';
    const XML_MSG91_API_DEVMODE = 'mobilelogin/smsgatways/msg91enabledev';

    public function __construct(\Magento\Framework\App\Helper\Context $context)
    {
        parent::__construct($context);
    }

    public function getTitle()
    {
        return __("Msg91");
    }

    public function getApiSenderId()
    {
        return $this->scopeConfig->getValue(
            self::XML_MSG91_API_SENDERID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getAuthKey()
    {
        return $this->scopeConfig->getValue(
            self::XML_MSG91_API_AUTHKEY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getRouter()
    {
        return $this->scopeConfig->getValue(
            self::XML_MSG91_API_ROUTER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getDevmode()
    {
            return $this->scopeConfig->getValue(
                self::XML_MSG91_API_DEVMODE,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
    }

    public function getApiUrl()
    {
        return $this->scopeConfig->getValue(
            self::XML_MSG91_API_URL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function validateSmsConfig()
    {
        return $this->getApiUrl() && $this->getAuthKey() && $this->getApiSenderId();
    }

    public function callApiUrl($message, $mobilenumbers, $dlt)
    {       
        try {
            $url = $this->getApiUrl();
            $authkey = $this->getAuthKey();
            $senderid = $this->getApiSenderId();
            $router = $this->getRouter();
            $devmode=$this->getDevmode();

            $ch = curl_init();
            if (!$ch) {
                return "Couldn't initialize a cURL handle";
            }
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt(
                $ch,
                CURLOPT_POSTFIELDS,
                "authkey=$authkey&mobiles=$mobilenumbers&message=$message&sender=$senderid&route=$router&country=0&DLT_TE_ID=$dlt&dev_mode=$devmode"
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $curlresponse = curl_exec($ch); // execute

            if (curl_errno($ch)) {
                curl_close($ch);
                 return ["status"=>true, "message"=>'Error: '.curl_error($ch)];
            }
            curl_close($ch);
            return ["status"=>true, "message"=>'Message Sent !!!'];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
