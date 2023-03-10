<?php
namespace Auraine\TransactionalSMS\Observer;

class SendSMSOnOrderSuccess implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * Order confirmed SMS config path
     * @var string const
     */
    private const CONFIG_PATH = "transaction_sms_control/order_confirm_sms/message";

    /**
     * @var \Auraine\TransactionalSMS\Helper\Data
     */
    private $helperData;

    /**
     * Constructor to get object of MageComp Mobilelogin helper.
     *
     * @param \Auraine\TransactionalSMS\Helper\Data $helperData
     */
    public function __construct(
        \Auraine\TransactionalSMS\Helper\Data $helperData,
    ) {
        $this->helperData = $helperData;
    }

    /**
     * @inheritdoc
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        $mobile = $order->getShippingAddress()->getTelephone();
        $deliveryDate = date("d-m-Y", strtotime(date("d-m-Y"). "+4 days"));

        if ($mobile !== null) {
            $this->helperData->orderSuccessSMS(
                self::CONFIG_PATH,
                $mobile,
                $deliveryDate,
                $order->getIncrementId()
            );
        }

        return $this;
    }
}
