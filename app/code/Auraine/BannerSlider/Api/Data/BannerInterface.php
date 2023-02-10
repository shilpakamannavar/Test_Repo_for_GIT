<?php

declare(strict_types=1);

namespace Auraine\BannerSlider\Api\Data;

interface BannerInterface
{
    /**
     * Get Entity Id
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set Entity Id
     *
     * @param int $entityId
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setEntityId($entityId);

    /**
     * Get Entity Id
     *
     * @return string
     */
    public function getSliderId(): int;

    /**
     * Set Slider Id
     *
     * @param int $sliderId
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setSliderId(int $sliderId): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * Get Resource Map Id
     *
     * @return int
     */
    public function getResourceMapId(): int;

    /**
     * Get Resource Map Id
     *
     * @param int $resourceMapId
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setResourceMapId(int $resourceMapId): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * Get Resource Type
     *
     * @return string|null
     */
    public function getResourceType(): ?string;

    /**
     * Set Resource Type
     *
     * @param string|null $resourceType
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setResourceType(?string $resourceType): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * Get Resource Path
     *
     * @return string|null
     */
    public function getResourcePath(): ?string;

    /**
     * Set Resource Path
     *
     * @param string|null $resourcePath
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setResourcePath(?string $resourcePath): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * Get Resource Path Mobile
     *
     * @return string|null
     */
    public function getResourcePathMobile(): ?string;

    /**
     * Set Resource Path
     *
     * @param string|null $resourcePathMobile
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setResourcePathMobile(?string $resourcePathMobile): \Auraine\BannerSlider\Api\Data\BannerInterface;
    
    /**
     * Get Is Enabled
     *
     * @return int
     */
    public function getIsEnabled(): int;

    /**
     * Set Is Enabled
     *
     * @param int $isEnabled
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setIsEnabled(int $isEnabled): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * Get Created At
     *
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * Set Created At
     *
     * @param string $createdAt
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setCreatedAt(string $createdAt): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * Get Updated At
     *
     * @return string
     */
    public function getUpdatedAt(): string;
    
    /**
     * Set Updated At
     *
     * @param string $updatedAt
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setUpdatedAt(string $updatedAt): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Set Title
     *
     * @param string $title
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setTitle(string $title): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * Get Alt Text
     *
     * @return string
     */
    public function getAltText(): string;

    /**
     * Set Alt Text
     *
     * @param string $altText
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setAltText(string $altText): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * Get Link
     *
     * @return string
     */
    public function getLink(): string;

    /**
     * Set Link
     *
     * @param string $link
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setLink(string $link): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * Get Sort Order
     *
     * @return int
     */
    public function getSortOrder(): int;

    /**
     * Set Sort Order
     *
     * @param int $sortOrder
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setSortOrder(int $sortOrder): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * Get Resource Map
     *
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface|null
     */
    public function getResourceMap(): ?\Auraine\BannerSlider\Api\Data\ResourceMapInterface;
}
