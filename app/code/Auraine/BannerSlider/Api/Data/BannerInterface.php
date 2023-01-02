<?php

declare(strict_types=1);

namespace Auraine\BannerSlider\Api\Data;


interface BannerInterface
{
    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $entityId
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setEntityId($entityId);

    /**
     * @return string
     */
    public function getSliderId(): int;

    /**
     * @param int $sliderId
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setSliderId(int $sliderId): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * @return int
     */
    public function getResourceMapId(): int;

    /**
     * @param int $resourceMapId
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setResourceMapId(int $resourceMapId): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * @return string|null
     */
    public function getResourceType(): ?string;

    /**
     * @param string|null $resourceType
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setResourceType(?string $resourceType): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * @return string|null
     */
    public function getResourcePath(): ?string;

    /**
     * @param string|null $resourcePath
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setResourcePath(?string $resourcePath): \Auraine\BannerSlider\Api\Data\BannerInterface;
    
    /**
     * @return int
     */
    public function getIsEnabled(): int;

    /**
     * @param int $isEnabled
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setIsEnabled(int $isEnabled): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @param string $createdAt
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setCreatedAt(string $createdAt): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * @param string $updatedAt
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setUpdatedAt(string $updatedAt): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @param string $title
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setTitle(string $title): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * @return string
     */
    public function getAltText(): string;

    /**
     * @param string $altText
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setAltText(string $altText): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * @return string
     */
    public function getLink(): string;

    /**
     * @param string $link
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setLink(string $link): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * @return int
     */
    public function getSortOrder(): int;

    /**
     * @param int $sortOrder
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function setSortOrder(int $sortOrder): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface|null
     */
    public function getResourceMap(): ?\Auraine\BannerSlider\Api\Data\ResourceMapInterface;
}