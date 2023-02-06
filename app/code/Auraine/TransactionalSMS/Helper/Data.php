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
     * @param string $deliveryDate
     * @param string $orderId
     * @return void
     */
    public function orderSuccessSMS($configPath, $mobile, $deliveryDate, $orderId)
    {
        $codes = ['{{delivery_date}}', '{{order_id}}'];
        $accurate = [$deliveryDate, $orderId];

        $message = $this->generateMessage($codes, $accurate, $configPath);

        $this->dispachSMS($message, $mobile);
    }

    /**
     * Send SMS on start of shipment.
     *
     * @param string $configPath
     * @param \Magento\Sales\Model\Order $order
     * @return void
     */
    public function shipmentShippedSMS($configPath, $order)
    {
        $orderId = $order->getIncrementId();
        $deliveryDate = date("d-m-Y", strtotime(date("d-m-Y"). "+4 days"));
        $mobile = $order->getShippingAddress()->getTelephone();
        $link = "https://www.glamourbook.com/";
        $quantity = $order->getTotalItemCount() < 2 ? '' : '+ '.$order->getTotalItemCount() - 1;
        
        $description = $this->getFirstFourWords($order->getAllItems()[0]->getName());

        $codes = ['{{order_id}}', '{{description}}', '{{quantity}}', '{{delivery_date}}', '{{link}}'];
        $accurate = [$orderId, $description, $quantity, $deliveryDate, $link];

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
    public function transactionSMS($configPath, $mobile, $grandTotal, $orderId)
    {
        $codes = ['{{amount}}', '{{order_id}}'];
        $accurate = [$grandTotal, $orderId];

        $message = $this->generateMessage($codes, $accurate, $configPath);

        $this->dispachSMS($message, $mobile);
    }

     /**
     * Send SMS on Cancelling the order.
     *
     * @param string $configPath
     * @param string $mobile
     * @param \Magento\Sales\Model\Order $order
     * @return void
     */
    public function orderDeliveredSMS($configPath, $mobile, $order)
    {
        $orderId = $order->getIncrementId();
        $quantity = $order->getTotalItemCount() < 2 ? '' : '+ '.$order->getTotalItemCount() - 1;
        $description = $this->getFirstFourWords($order->getAllItems()[0]->getName());

        $codes = ['{{order_id}}', '{{description}}', '{{quantity}}'];
        $accurate = [$orderId, $description, $quantity];

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
    public function returnInitiatedSMS($configPath, $mobile, $order)
    {
        $codes = ['{{order_id}}', '{{description}}', '{{no_of_days}}'];

        $accurate = [
            $order->getIncrementId(),
            $this->getFirstFourWords($order->getAllItems()[0]->getName()),
            4
        ];

        $message = $this->generateMessage($codes, $accurate, $configPath);

        $this->dispachSMS($message, $mobile);
    }

    /**
     * Send SMS on products not able to deliver.
     *
     * @param string $configPath
     * @param string $mobile
     * @param string $orderId
     * @return void
     */
    public function shipmentNotDelivered(
        $configPath,
        $mobile,
        $orderId
    ) {
        $codes = ['{{order_id}}'];
        $accurate = [$orderId];

        $message = $this->generateMessage($codes, $accurate, $configPath);

        $this->dispachSMS($message, $mobile);
    }

    /**
     * Send SMS on Cancelling the order.
     *
     * @param string $configPath
     * @param string $mobile
     * @param \Magento\Sales\Model\Order $order
     * @return void
     */
    public function shipmentCancelledSMS(
        $configPath,
        $mobile,
        $order
    ) {
        $codes = ['{{order_id}}', '{{description}}', '{{quantity}}'];
        $accurate = [
            $order->getIncrementId(),
            $this->getFirstFourWords($order->getAllItems()[0]->getName()),
            $order->getTotalItemCount() - 1];

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
        $message = $this->getConfigValue($configPath);

        if (empty($codes) && empty($accurate)) {
            return $message;
        }

        return str_replace($codes, $accurate, $message);
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

    /**
     * Fetch first 4 words of a string
     *
     * @param string $title
     * @return string
     */
    private function getFirstFourWords($title)
    {
        $titleArray = explode(' ', $title);

        if (count($titleArray) > 4) {
            return implode(' ', array_slice(explode(' ', $title), 0, 4));
        }

        return implode(' ', array_slice(explode(' ', $title), 0, count($titleArray)));
    }
}
