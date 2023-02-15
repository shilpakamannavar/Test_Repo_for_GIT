<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\PushNotification\Api\Data;

interface TemplateInterface
{

    const IDENTIFIER = 'identifier';
    const DESTINATION_URL = 'destination_url';
    const STATUS = 'status';
    const TEMPLATE_ID = 'template_id';
    const CONTENT = 'content';
    const ICON = 'image';
    const SUBJECT = 'subject';

    /**
     * Get template_id
     * @return string|null
     */
    public function getTemplateId();

    /**
     * Set template_id
     * @param string $templateId
     * @return \Auraine\PushNotification\Template\Api\Data\TemplateInterface
     */
    public function setTemplateId($templateId);

    /**
     * Get identifier
     * @return string|null
     */
    public function getIdentifier();

    /**
     * Set identifier
     * @param string $identifier
     * @return \Auraine\PushNotification\Template\Api\Data\TemplateInterface
     */
    public function setIdentifier($identifier);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \Auraine\PushNotification\Template\Api\Data\TemplateInterface
     */
    public function setStatus($status);

    /**
     * Get subject
     * @return string|null
     */
    public function getSubject();

    /**
     * Set subject
     * @param string $subject
     * @return \Auraine\PushNotification\Template\Api\Data\TemplateInterface
     */
    public function setSubject($subject);

    /**
     * Get content
     * @return string|null
     */
    public function getContent();

    /**
     * Set content
     * @param string $content
     * @return \Auraine\PushNotification\Template\Api\Data\TemplateInterface
     */
    public function setContent($content);

    /**
     * Get destination_url
     * @return string|null
     */
    public function getDestinationUrl();

    /**
     * Set destination_url
     * @param string $destinationUrl
     * @return \Auraine\PushNotification\Template\Api\Data\TemplateInterface
     */
    public function setDestinationUrl($destinationUrl);

    /**
     * Get icon
     * @return string|null
     */
    public function getIcon();

    /**
     * Set icon
     * @param string $icon
     * @return \Auraine\PushNotification\Template\Api\Data\TemplateInterface
     */
    public function setIcon($icon);
}

