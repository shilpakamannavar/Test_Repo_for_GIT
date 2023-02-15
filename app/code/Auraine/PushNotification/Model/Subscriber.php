<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\PushNotification\Model;

use Auraine\PushNotification\Api\Data\SubscriberInterface;
use Magento\Framework\Model\AbstractModel;

class Subscriber extends AbstractModel implements SubscriberInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Auraine\PushNotification\Model\ResourceModel\Subscriber::class);
    }

    /**
     * @inheritDoc
     */
    public function getSubscriberId()
    {
        return $this->getData(self::SUBSCRIBER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setSubscriberId($subscriberId)
    {
        return $this->setData(self::SUBSCRIBER_ID, $subscriberId);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @inheritDoc
     */
    public function getDateOfBirth()
    {
        return $this->getData(self::DATE_OF_BIRTH);
    }

    /**
     * @inheritDoc
     */
    public function setDateOfBirth($dateOfBirth)
    {
        return $this->setData(self::DATE_OF_BIRTH, $dateOfBirth);
    }

    /**
     * @inheritDoc
     */
    public function getGender()
    {
        return $this->getData(self::GENDER);
    }

    /**
     * @inheritDoc
     */
    public function setGender($gender)
    {
        return $this->setData(self::GENDER, $gender);
    }

    /**
     * @inheritDoc
     */
    public function getDeviceType()
    {
        return $this->getData(self::DEVICE_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setDeviceType($deviceType)
    {
        return $this->setData(self::DEVICE_TYPE, $deviceType);
    }

    /**
     * @inheritDoc
     */
    public function getToken()
    {
        return $this->getData(self::TOKEN);
    }

    /**
     * @inheritDoc
     */
    public function setToken($token)
    {
        return $this->setData(self::TOKEN, $token);
    }

    /**
     * @inheritDoc
     */
    public function getBrowser()
    {
        return $this->getData(self::BROWSER);
    }

    /**
     * @inheritDoc
     */
    public function setBrowser($browser)
    {
        return $this->setData(self::BROWSER, $browser);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedDate()
    {
        return $this->getData(self::CREATED_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedDate($createdDate)
    {
        return $this->setData(self::CREATED_DATE, $createdDate);
    }

    /**
     * @inheritDoc
     */
    public function getIpAddress()
    {
        return $this->getData(self::IP_ADDRESS);
    }

    /**
     * @inheritDoc
     */
    public function setIpAddress($ipAddress)
    {
        return $this->setData(self::IP_ADDRESS, $ipAddress);
    }

    /**
     * @inheritDoc
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @inheritDoc
     */
    public function getNotificationType()
    {
        return $this->getData(self::NOTIFICATION_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setNotificationType($notificationType)
    {
        return $this->setData(self::NOTIFICATION_TYPE, $notificationType);
    }
}

