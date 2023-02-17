<?php
namespace Auraine\TransactionalEmail\Model\Mail;

use Magento\Sales\Model\Order;

class TransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder
{
    public function orderConformation(Order $order)
    {
       return $this->sendTransactionalEmail($order ,'auraine_transactionalemail_order_email_template');
    }
    public function deliverd( Order $order)
    {
        return $this->sendTransactionalEmail($order ,'auraine_transactionalemail_order_email_delivered');
    }
    public function shipped($order)
    {
        return $this->sendTransactionalEmail($order ,'auraine_transactionalemail_order_email_shipped');
    }
    public function cancelled($order)
    {
        return $this->sendTransactionalEmail($order ,'auraine_transactionalemail_oredr_email_cancelled');
    }
    public function notDeliverd($order)
    {
        return $this->sendTransactionalEmail($order ,'auraine_transactionalemail_oredr_email_not_delivered');
    }
    public function refunded($order)
    {
        return $this->sendTransactionalEmail($order ,'auraine_transactionalemail_oredr_email_refunded');
    }
    public function returnIntiated($order)
    {
        return $this->sendTransactionalEmail($order ,'auraine_transactionalemail_oredr_email_return_initiated');
    }
    public function returnPicked($order)
    {
        return $this->sendTransactionalEmail($order ,'auraine_transactionalemail_oredr_email_return_picked');
    }
    
    public function sendTransactionalEmail( Order $order, $templateIdentifier)
    {
        $templateVars = [
            'order' => $order,
            'layout' => [
                'area' => 'frontend',
                'handle' => 'sales_email_order_items',
                'order' => $order
            ]
        ];

        $this->setTemplateIdentifier($templateIdentifier)
            ->setTemplateOptions([
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $order->getStoreId(),
            ])
            ->setTemplateVars($templateVars)
            ->setFrom('general')
            ->addTo($order->getCustomerEmail())
            ->getTransport()
            ->sendMessage();

        return $this;
    }
}
