<?php

declare(strict_types=1);

namespace Auraine\PushNotification\Api\Data;

interface SubscriberInterface
{

    const CUSTOMER_ID = 'customer_id';
    const BROWSER = 'browser';
    const DATE_OF_BIRTH = 'date_of_birth';
    const NOTIFICATION_TYPE = 'notification_type';
    const TOKEN = 'token';
    const IP_ADDRESS = 'ip_address';
    const GENDER = 'gender';
    const ENTITY_ID = 'entity_id';
    const DEVICE_TYPE = 'device_type';
    const CREATED_DATE = 'created_date';
    const SUBSCRIBER_ID = 'subscriber_id';

    /**
     * Get subscriber_id
     * @return string|null
     */
    public function getSubscriberId();

    /**
     * Set subscriber_id
     * @param string $subscriberId
     * @return \Auraine\PushNotification\Subscriber\Api\Data\SubscriberInterface
     */
    public function setSubscriberId($subscriberId);

    /**
     * Get customer_id
     * @return string|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param string $customerId
     * @return \Auraine\PushNotification\Subscriber\Api\Data\SubscriberInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get date_of_birth
     * @return string|null
     */
    public function getDateOfBirth();

    /**
     * Set date_of_birth
     * @param string $dateOfBirth
     * @return \Auraine\PushNotification\Subscriber\Api\Data\SubscriberInterface
     */
    public function setDateOfBirth($dateOfBirth);

    /**
     * Get gender
     * @return string|null
     */
    public function getGender();

    /**
     * Set gender
     * @param string $gender
     * @return \Auraine\PushNotification\Subscriber\Api\Data\SubscriberInterface
     */
    public function setGender($gender);

    /**
     * Get device_type
     * @return string|null
     */
    public function getDeviceType();

    /**
     * Set device_type
     * @param string $deviceType
     * @return \Auraine\PushNotification\Subscriber\Api\Data\SubscriberInterface
     */
    public function setDeviceType($deviceType);

    /**
     * Get token
     * @return string|null
     */
    public function getToken();

    /**
     * Set token
     * @param string $token
     * @return \Auraine\PushNotification\Subscriber\Api\Data\SubscriberInterface
     */
    public function setToken($token);

    /**
     * Get browser
     * @return string|null
     */
    public function getBrowser();

    /**
     * Set browser
     * @param string $browser
     * @return \Auraine\PushNotification\Subscriber\Api\Data\SubscriberInterface
     */
    public function setBrowser($browser);

    /**
     * Get created_date
     * @return string|null
     */
    public function getCreatedDate();

    /**
     * Set created_date
     * @param string $createdDate
     * @return \Auraine\PushNotification\Subscriber\Api\Data\SubscriberInterface
     */
    public function setCreatedDate($createdDate);

    /**
     * Get ip_address
     * @return string|null
     */
    public function getIpAddress();

    /**
     * Set ip_address
     * @param string $ipAddress
     * @return \Auraine\PushNotification\Subscriber\Api\Data\SubscriberInterface
     */
    public function setIpAddress($ipAddress);

    /**
     * Get entity_id
     * @return string|null
     */
    public function getEntityId();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Auraine\PushNotification\Subscriber\Api\Data\SubscriberInterface
     */
    public function setEntityId($entityId);

    /**
     * Get notification_type
     * @return string|null
     */
    public function getNotificationType();

    /**
     * Set notification_type
     * @param string $notificationType
     * @return \Auraine\PushNotification\Subscriber\Api\Data\SubscriberInterface
     */
    public function setNotificationType($notificationType);
}

