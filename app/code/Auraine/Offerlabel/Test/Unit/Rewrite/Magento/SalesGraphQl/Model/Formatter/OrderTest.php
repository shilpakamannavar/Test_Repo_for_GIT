<?php
use PHPUnit\Framework\TestCase;
use Auraine\Offerlabel\Rewrite\Magento\SalesGraphQl\Model\Formatter\Order;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\SalesGraphQl\Model\Order\OrderAddress;
use Magento\SalesGraphQl\Model\Order\OrderPayments;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class OrderTest extends TestCase
{
    public function testFormatMethod()
    {
        // Create mock objects for the dependencies
        $orderAddress = $this->createMock(OrderAddress::class);
        $orderPayments = $this->createMock(OrderPayments::class);
        $timezone = $this->createMock(TimezoneInterface::class);
        
        // Create an instance of the class we want to test
        $orderFormatter = new Order($orderAddress, $orderPayments, $timezone);
        
        // Create a mock OrderInterface object to pass to the format method
        $order = $this->createMock(OrderInterface::class);
        $order->method('getCreatedAt')->willReturn('2022-03-15 12:34:56');
        $order->method('getGrandTotal')->willReturn(99.99);
        $order->method('getEntityId')->willReturn(12345);
        $order->method('getIncrementId')->willReturn('000000001');
        $order->method('getStatus')->willReturn('Complete');
        $order->method('getShippingDescription')->willReturn('Flat Rate - Fixed');
        
        // Set up expectations for the mocked objects
        $orderAddress->expects($this->once())->method('getOrderShippingAddress')->with($order);
        $orderAddress->expects($this->once())->method('getOrderBillingAddress')->with($order);
        $orderPayments->expects($this->once())->method('getOrderPaymentMethod')->with($order);
        $timezone->expects($this->once())->method('getConfigTimezone')->willReturn('UTC');
        
        // Call the format method and assert that the returned array has the expected values
        $expectedResult = [
            'created_at' => '2022-03-15 19:34:56',
            'grand_total' => 99.99,
            'id' => 'MTIzNDU=',
            'increment_id' => '000000001',
            'number' => '000000001',
            'order_date' => '2022-03-15 19:34:56',
            'order_number' => '000000001',
            'status' => 'Complete',
            'shipping_method' => 'Flat Rate - Fixed',
            'shipping_address' => null,
            'billing_address' => null,
            'payment_methods' => [],
            'model' => $order,
        ];
        $this->assertEquals($expectedResult, $orderFormatter->format($order));
    }
}
