<?php
namespace Auraine\TransactionalSMS\Observer;

use Magento\Sales\Model\Order;

class SendSMSOnOrderStatusChange implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * Shipped SMS config path
     * @var string const
     */
    private const SHIPPED_CONFIG = "transaction_sms_control/shipped_sms/message";

    /**
     * Delivered SMS config path
     * @var string const
     */
    private const DELIVERED_CONFIG = "transaction_sms_control/delivered_sms/message";

    /**
     * Cancelled SMS config path
     * @var string const
     */
    private const CANCELLED_CONFIG = "transaction_sms_control/cancelled_sms/message";

    /**
     * Not delivered SMS config path
     * @var string const
     */
    private const NOT_DELIVERED_CONFIG = "transaction_sms_control/not_delivered_sms/message";

    /**
     * Return inititated SMS config path
     * @var string const
     */
    private const RETURN_INITITATED_CONFIG = "transaction_sms_control/return_inititated_sms/message";

    /**
     * Return picked up SMS config path
     * @var string const
     */
    private const RETURN_PICKED_CONFIG = "transaction_sms_control/return_picked_sms/message";

    /**
     * Refund success SMS config path
     * @var string const
     */
    private const REFUND_SUCCESS_CONFIG = "transaction_sms_control/refund_success/message";

    /**
     * @var \Auraine\TransactionalSMS\Helper\Data
     */
    protected $_helperData;

    /**
     * Constructs Loyalty point creation service object.
     *
     * @param \Auraine\TransactionalSMS\Helper\Data $helperData
     */
    public function __construct(
        \Auraine\TransactionalSMS\Helper\Data $helperData
    ) {
        $this->_helperData = $helperData;
    }

    /**
     * @inheritdoc
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $mobile = $order->getShippingAddress()->getTelephone();

        if ($order instanceof \Magento\Framework\Model\AbstractModel && $mobile !== null) {
            $this->sendSMS($order, $mobile);
        }

        return $this;
    }

    /**
     * Send SMS based on the state of the order
     *
     * @param \Magento\Sales\Model\Order $order
     * @param string $mobile
     *
     * @return void
     */
    private function sendSMS($order, $mobile)
    {
        $orderId = $order->getIncrementId();
        $name = $order->getCustomerFirstName() . ' ' . $order->getCustomerLastName();

        switch ($order->getState()) {
            case Order::STATE_PROCESSING:
                // Order Processing (Shipped)
                $this->_helperData->shipmentShippedSMS(
                    self::SHIPPED_CONFIG,
                    $order
                );
                break;

            case Order::STATE_COMPLETE:
                // Order completed (Delivered)
                $this->_helperData->orderDeliveredSMS(
                    self::DELIVERED_CONFIG,
                    $mobile,
                    $order
                );
                break;
            
            case Order::STATE_HOLDED:
                // Order cancelled
                $this->_helperData->shipmentCancelledSMS(
                    self::CANCELLED_CONFIG,
                    $mobile,
                    $order
                );
                break;
            
            case Order::STATE_CANCELED:
                 // Order Not Delivered
                 $this->_helperData->shipmentNotDelivered(
                     self::NOT_DELIVERED_CONFIG,
                     $mobile,
                     $orderId
                 );
                break;
            
            case "return":
                // Return initiated
                $this->_helperData->returnInitiatedSMS(
                    self::RETURN_INITITATED_CONFIG,
                    $mobile,
                    $order
                );
                break;

            case "picked":
                // Return order picked up
                $this->_helperData->transactionSMS(
                    self::RETURN_PICKED_CONFIG,
                    $mobile,
                    $name,
                    $orderId
                );
                break;

            case "refunded":
                // Amount refunded
                $this->_helperData->transactionSMS(
                    self::REFUND_SUCCESS_CONFIG,
                    $mobile,
                    $order->getGrandTotal(),
                    $orderId
                );
                break;
            
            default:
                break;
        }
    }
}
