<?php
namespace Auraine\TransactionalSMS\Helper;

class Data
{
    /**
     * Transaction SMS status config path
     * @var string const
     */
    private const OTP_STATUS_PATH = "transaction_sms_control/transaction_sms/enable_sms";

    /**
     * @var \Magecomp\Mobilelogin\Helper\Data
     */
    private $_helperData;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $_scopeConfig;

    /**
     * Constructor to get object of MageComp Mobilelogin helper.
     *
     * @param \Magecomp\Mobilelogin\Helper\Data $helperData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magecomp\Mobilelogin\Helper\Data $helperData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_helperData = $helperData;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Send SMS method to validate the incoming mobile number and perform the action else it won't send.
     *
     * @param string $configPath
     * @param string $mobile
     * @param string $customerName
     * @return void
     */
    public function customerRegisterSuccessSMS($configPath, $mobile, $customerName)
    {
        $codes = ['{{customer_name}}'];
        $accurate = [$customerName];

        $message = $this->generateMessage($codes, $accurate, $configPath);

        $this->dispachSMS($message, $mobile);
    }

    /**
     * Send SMS on successfully placing order.
     *
     * @param string $configPath
     * @param string $mobile
     * @param string $customerName
     * @param string $orderId
     * @return void
     */
    public function orderSuccessSMS($configPath, $mobile, $customerName, $orderId)
    {
        $codes = ['{{customer_name}}', '{{order_id}}'];
        $accurate = [$customerName, $orderId];

        $message = $this->generateMessage($codes, $accurate, $configPath);

        $this->dispachSMS($message, $mobile);
    }

    /**
     * Send SMS on start of shipment.
     *
     * @param string $configPath
     * @param string $mobile
     * @param string $orderId
     * @param string $shipmentId
     * @return void
     */
    public function shipmentShippedSMS($configPath, $mobile, $orderId, $shipmentId)
    {
        $codes = ['{{order_id}}', '{{shipment}}'];
        $accurate = [$orderId, $shipmentId];

        $message = $this->generateMessage($codes, $accurate, $configPath);

        $this->dispachSMS($message, $mobile);
    }

    /**
     * Send SMS on various occations where customer name & order id are involved.
     *
     * @param string $configPath
     * @param string $mobile
     * @param string $customerName
     * @param string $orderId
     * @return void
     */
    public function transactionSMS($configPath, $mobile, $customerName, $orderId)
    {
        $codes = ['{{customer_name}}', '{{order_id}}'];
        $accurate = [$customerName, $orderId];

        $message = $this->generateMessage($codes, $accurate, $configPath);

        $this->dispachSMS($message, $mobile);
    }

    /**
     * Send SMS on Cancelling the order / Not able to deliver.
     *
     * @param string $configPath
     * @param string $mobile
     * @param string $customerName
     * @param string $orderId
     * @param string $shipmentId
     * @return void
     */
    public function shipmentCancelledOrNotDeliveredGenericSMS(
        $configPath,
        $mobile,
        $customerName,
        $orderId,
        $shipmentId
    ) {
        $codes = ['{{customer_name}}', '{{order_id}}', '{{shipment}}'];
        $accurate = [$customerName, $orderId, $shipmentId];

        $message = $this->generateMessage($codes, $accurate, $configPath);

        $this->dispachSMS($message, $mobile);
    }

    /**
     * Generate message string.
     *
     * @param string $codes
     * @param string $accurate
     * @param string $configPath
     * @return string
     */
    private function generateMessage($codes, $accurate, $configPath)
    {
        return str_replace($codes, $accurate, $this->getConfigValue($configPath));
    }

    /**
     * Dispach SMS
     *
     * @param string $message
     * @param string $mobile
     * @return void
     */
    private function dispachSMS($message, $mobile)
    {
        $otpStatus = $this->getConfigValue(self::OTP_STATUS_PATH);

        if (strlen($mobile) == 12 && $otpStatus) {
            $this->_helperData->callApiUrl($message, $mobile, 1);
        } elseif (strlen($mobile) == 10 && $otpStatus) {
            $this->_helperData->callApiUrl($message, "91".$mobile, 1);
        }
    }

    /**
     * Fetch OTP config status
     *
     * @param string $path
     * @return string
     */
    private function getConfigValue($path)
    {
        return $this->_scopeConfig
            ->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
