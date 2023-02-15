<?php
namespace Auraine\PushNotification\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Auraine\PushNotification\Model\SubscriberFactory;

class SubscriberInput implements ResolverInterface
{
    private $subscriberFactory;
 
    public function __construct(SubscriberFactory $subscriberFactory)
    {
        $this->subscriberFactory = $subscriberFactory;
    }
 
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (empty($args['input']) || !is_array($args['input'])) {
            throw new GraphQlInputException(
                __('"Input" value should be specified and in the form of array.')
            );
        }
 
        $data = $args['input'];
        $token = $data['token'];
        $browser = $data['browser'] ?? null;
        $gender = $data['gender'] ?? null;
        $customer_id = $data['customer_id'] ?? null;
        $ip_address = $data['ip_address'] ?? null;
        $device_type = $data['device_type'] ?? null;
        $date_of_birth = $data['date_of_birth'] ?? null;
        $created_date = date('Y-m-d');

 
        if (empty($token)) {
            throw new GraphQlInputException(__('Token is a required field.'));
        }
        if (empty($browser)) {
            throw new GraphQlInputException(__('Browser is a required field.'));
        }
        if (empty($gender)) {
            throw new GraphQlInputException(__('Gender is a required field.'));
        }
        if (empty($customer_id)) {
            throw new GraphQlInputException(__('Customer Id is a required field.'));
        }
        if (empty($ip_address)) {
            throw new GraphQlInputException(__('IP Address is a required field.'));
        }
        if (empty($device_type)) {
            throw new GraphQlInputException(__('Device Type is a required field.'));
        }
        if (empty($date_of_birth)) {
            throw new GraphQlInputException(__('date_of_birth is a required field.'));
        }
 
        $subscriber = $this->subscriberFactory->create();
        $subscriber->setToken($token);
        $subscriber->setBrowser($browser);
        $subscriber->setGender($gender);
        $subscriber->setCustomerId($customer_id);
        $subscriber->setIpAddress($ip_address);
        $subscriber->setDeviceType($device_type);
        $subscriber->setDateOfBirth($date_of_birth);
        $subscriber->setCreatedDate($created_date);

        $subscriber->save();
 
        return [
            'subscriber_id' => $subscriber->getData('subscriber_id'),
            'token' => $subscriber->getData('token'),
            'browser' => $subscriber->getData('browser'),
            'date_of_birth' => $subscriber->getData('date_of_birth'),
            'device_type' => $subscriber->getData('device_type'),
            'customer_id' => $subscriber->getData('customer_id'),
            'ip_address' => $subscriber->getData('ip_address'),
            'created_date' => $subscriber->getData('created_date'),
        ];
    }
}
