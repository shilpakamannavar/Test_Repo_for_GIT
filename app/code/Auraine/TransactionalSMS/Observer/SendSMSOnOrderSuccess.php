<?php
namespace Auraine\TransactionalSMS\Observer;

class SendSMSOnOrderSuccess implements \Magento\Framework\Event\ObserverInterface
{

    private const CONFIG_PATH = "transaction_sms_control/order_confirm_sms/message";

    /**
     * @var \Auraine\TransactionalSMS\Helper\Data
     */
    private $_helperData;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $_scopeConfig;

    /**
     * Constructor to get object of MageComp Mobilelogin helper.
     *
     * @param \Auraine\TransactionalSMS\Helper\Data $helperData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Auraine\TransactionalSMS\Helper\Data $helperData,
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

        $name = $order->getCustomerFirstName() . ' ' . $order->getCustomerLastName();
        $mobile = $order->getShippingAddress()->getTelephone();

        if ($mobile !== null) {
            $this->_helperData->orderSuccessSMS(
                self::CONFIG_PATH,
                $mobile,
                $name,
                $order->getIncrementId()
            );
        }

        return $this;
    }
}
