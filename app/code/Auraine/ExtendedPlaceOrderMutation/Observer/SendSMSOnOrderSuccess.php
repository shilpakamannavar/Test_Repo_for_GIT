<?php
namespace Auraine\ExtendedPlaceOrderMutation\Observer;

class SendSMSOnOrderSuccess implements \Magento\Framework\Event\ObserverInterface
{
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
     * @param \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public function __construct(
        \Magecomp\Mobilelogin\Helper\Data $helperData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    ) {
        $this->_helperData = $helperData;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @inheritdoc
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if ($order->getShippingAddress()->getTelephone() !== null) {
            $this->sendSMS($order);
        }

        return $this;
    }

    /**
     * Send SMS method to validate the incoming mobile number and perform the action else it won't send.
     *
     * @param \Magento\Sales\Model\Order $order
     * @return void
     */
    private function sendSMS($order)
    {
        $name = $order->getCustomerFirstName() . ' ' . $order->getCustomerLastName();
        $mobile = $order->getShippingAddress()->getTelephone();

        $message = $this->generateMessage($name, $order->getIncrementId());

        if (strlen($mobile) == 12 && $this->_scopeConfig->getValue("place_order/place_otp/enable_otp", \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            $this->_helperData->callApiUrl($message, $mobile, 1);
        } elseif (strlen($mobile) == 10 && $this->_scopeConfig->getValue("place_order/place_otp/enable_otp", \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            $this->_helperData->callApiUrl($message, "91".$mobile, 1);
        }
    }

    /**
     * Generate message string.
     *
     * @param string $name
     * @param string $orderId
     * @return boolean
     */
    private function generateMessage($name, $orderId)
    {
        return __("$name, Thanks placing order. \nYour order number: $orderId will be processed soon...");
    }
}
