<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Staticcontent\Api\Data;

interface ContentInterface
{

    const ENABLE = 'enable';
    const TYPE = 'type';
    const VALUE = 'value';
    const CONTENT_ID = 'content_id';
    const SORTORDER = 'sortorder';
    const LABEL = 'label';

    /**
     * Get content_id
     * @return string|null
     */
    public function getContentId();

    /**
     * Set content_id
     * @param string $contentId
     * @return \Auraine\Staticcontent\Content\Api\Data\ContentInterface
     */
    public function setContentId($contentId);

    /**
     * Get type
     * @return string|null
     */
    public function getType();

    /**
     * Set type
     * @param string $type
     * @return \Auraine\Staticcontent\Content\Api\Data\ContentInterface
     */
    public function setType($type);

    /**
     * Get label
     * @return string|null
     */
    public function getLabel();

    /**
     * Set label
     * @param string $label
     * @return \Auraine\Staticcontent\Content\Api\Data\ContentInterface
     */
    public function setLabel($label);

    /**
     * Get value
     * @return string|null
     */
    public function getValue();

    /**
     * Set value
     * @param string $value
     * @return \Auraine\Staticcontent\Content\Api\Data\ContentInterface
     */
    public function setValue($value);

    /**
     * Get sortorder
     * @return string|null
     */
    public function getSortorder();

    /**
     * Set sortorder
     * @param string $sortorder
     * @return \Auraine\Staticcontent\Content\Api\Data\ContentInterface
     */
    public function setSortorder($sortorder);

    /**
     * Get enable
     * @return string|null
     */
    public function getEnable();

    /**
     * Set enable
     * @param string $enable
     * @return \Auraine\Staticcontent\Content\Api\Data\ContentInterface
     */
    public function setEnable($enable);
}
