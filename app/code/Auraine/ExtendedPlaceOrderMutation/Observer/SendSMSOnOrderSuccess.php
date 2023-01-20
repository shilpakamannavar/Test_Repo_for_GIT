<?php
namespace Auraine\ExtendedPlaceOrderMutation\Observer;

class SendSMSOnOrderSuccess implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magecomp\Mobilelogin\Helper\Data
     */
    private $_helperData;

    /**
     * Constructor to get object of MageComp Mobilelogin helper.
     * 
     * @param \Magecomp\Mobilelogin\Helper\Data $helperData
     */
    public function __construct(
        \Magecomp\Mobilelogin\Helper\Data $helperData)
    {
        $this->_helperData = $helperData;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if (!is_null($order->getShippingAddress()->getTelephone())) {
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

        if (strlen($mobile) == 12) {
            $this->_helperData->callApiUrl($message, $mobile, 1);
        } else if (strlen($mobile) == 10) {
            $this->_helperData->callApiUrl($message, "91".$mobile, 1);
        }
    }

    /**
     * Generate message string.
     * 
     * @param string $order
     * @param string $order
     * @return boolean
     */
    private function generateMessage($name, $orderId) {
        return __("$name, Thanks placing order. \nYour order number: $orderId will be processed soon...");
    }

}
