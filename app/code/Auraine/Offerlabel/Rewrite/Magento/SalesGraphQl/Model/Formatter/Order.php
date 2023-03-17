<?php
namespace Auraine\Offerlabel\Rewrite\Magento\SalesGraphQl\Model\Formatter;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\SalesGraphQl\Model\Order\OrderAddress;
use Magento\SalesGraphQl\Model\Order\OrderPayments;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Format order model for graphql schema
 */
class Order
{
    /**
     * @var OrderAddress
     */
    private $orderAddress;

    /**
     * @var OrderPayments
     */
    private $orderPayments;
     /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @param OrderAddress $orderAddress
     * @param OrderPayments $orderPayments
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        OrderAddress $orderAddress,
        OrderPayments $orderPayments,
        TimezoneInterface $timezone
    ) {
        $this->orderAddress = $orderAddress;
        $this->orderPayments = $orderPayments;
        $this->timezone = $timezone;
    }

    /**
     * Format order model for graphql schema
     *
     * @param OrderInterface $orderModel
     * @return array
     */
    public function format(OrderInterface $orderModel): array
    {
        $createdAt = new \DateTime($orderModel->getCreatedAt());
        $createdAt->setTimezone(new \DateTimeZone($this->timezone->getConfigTimezone()));
        
        return [
            'created_at' => $createdAt->format('Y-m-d H:i:s'),
            'grand_total' => $orderModel->getGrandTotal(),
            'id' => base64_encode($orderModel->getEntityId()),
            'increment_id' => $orderModel->getIncrementId(),
            'number' => $orderModel->getIncrementId(),
            'order_date' => $createdAt->format('Y-m-d H:i:s'),
            'order_number' => $orderModel->getIncrementId(),
            'status' => $orderModel->getStatus(),
            'shipping_method' => $orderModel->getShippingDescription(),
            'shipping_address' => $this->orderAddress->getOrderShippingAddress($orderModel),
            'billing_address' => $this->orderAddress->getOrderBillingAddress($orderModel),
            'payment_methods' => $this->orderPayments->getOrderPaymentMethod($orderModel),
            'model' => $orderModel,
        ];
    }
}
