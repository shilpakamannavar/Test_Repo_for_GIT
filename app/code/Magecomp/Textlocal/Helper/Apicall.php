<?php
namespace Magecomp\Textlocal\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Phrase;
use Magento\Store\Model\ScopeInterface;

class Apicall extends AbstractHelper
{
    const XML_TEXTLOCAL_API_AUTHKEY = 'mobilelogin/smsgatways/textlocalauthkey';
    const XML_TEXTLOCAL_API_URL = 'mobilelogin/smsgatways/textlocalapiurl';
    const XML_TEXTLOCAL_SENDER_ID = 'mobilelogin/smsgatways/textlocalsenderid';

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * @return Phrase
     */
    public function getTitle(): Phrase
    {
        return __("Textlocal");
    }

    /**
     * @return string
     */
    public function getApiSenderId(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_TEXTLOCAL_SENDER_ID,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getAuthKey(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_TEXTLOCAL_API_AUTHKEY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_TEXTLOCAL_API_URL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function validateSmsConfig(): bool
    {
        return $this->getApiUrl() && $this->getAuthKey() && $this->getApiSenderId();
    }

    /**
     * @param $message
     * @param $mobileNumbers
     * @param $dlt
     * @return array|string
     */
    public function callApiUrl($message, $mobileNumbers, $dlt)
    {
        try {
            $url = $this->getApiUrl();
            $apiKey = urlencode($this->getAuthKey());
            $senderId = $this->getApiSenderId();
            $sender = urlencode($senderId);

            $data = [
                'apikey' => $apiKey,
                'numbers' => $mobileNumbers,
                'sender' => $sender,
                'message' => rawurlencode($message)
            ];

            $ch = curl_init();
            if (!$ch) {
                return "Couldn't initialize a cURL handle";
            }

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_exec($ch);

            if (curl_errno($ch)) {
                curl_close($ch);
                return ["status" => true, "message"=>'Error: '.curl_error($ch)];
            }
            $response = curl_close($ch);
            return ["status" => true, "message" => 'Message Sent !!!'];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
