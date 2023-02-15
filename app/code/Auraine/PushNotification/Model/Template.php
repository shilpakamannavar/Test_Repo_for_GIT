<?php

declare(strict_types=1);

namespace Auraine\PushNotification\Model;

use Auraine\PushNotification\Api\Data\TemplateInterface;
use Magento\Framework\Model\AbstractModel;

class Template extends AbstractModel implements TemplateInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Auraine\PushNotification\Model\ResourceModel\Template::class);
    }

    /**
     * @inheritDoc
     */
    public function getTemplateId()
    {
        return $this->getData(self::TEMPLATE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setTemplateId($templateId)
    {
        return $this->setData(self::TEMPLATE_ID, $templateId);
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return $this->getData(self::IDENTIFIER);
    }

    /**
     * @inheritDoc
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getSubject()
    {
        return $this->getData(self::SUBJECT);
    }

    /**
     * @inheritDoc
     */
    public function setSubject($subject)
    {
        return $this->setData(self::SUBJECT, $subject);
    }

    /**
     * @inheritDoc
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * @inheritDoc
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * @inheritDoc
     */
    public function getDestinationUrl()
    {
        return $this->getData(self::DESTINATION_URL);
    }

    /**
     * @inheritDoc
     */
    public function setDestinationUrl($destinationUrl)
    {
        return $this->setData(self::DESTINATION_URL, $destinationUrl);
    }

    /**
     * @inheritDoc
     */
    public function getIcon()
    {
        return $this->getData(self::ICON);
    }

    /**
     * @inheritDoc
     */
    public function setIcon($icon)
    {
        return $this->setData(self::ICON, $icon);
    }
}

