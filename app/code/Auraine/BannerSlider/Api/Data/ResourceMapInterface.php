<?php

declare(strict_types=1);

namespace Auraine\BannerSlider\Api\Data;


interface ResourceMapInterface
{
    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $entityId
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     */
    public function setEntityId($entityId);

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @param string $title
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     */
    public function setTitle(string $title): \Auraine\BannerSlider\Api\Data\ResourceMapInterface;

    /**
     * @return int|null
     */
    public function getMinWidth(): ?int;

    /**
     * @param int|null $minWidth
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     */
    public function setMinWidth(?int $minWidth): \Auraine\BannerSlider\Api\Data\ResourceMapInterface;

    /**
     * @return int|null
     */
    public function getMaxWidth(): ?int;

    /**
     * @param int|null $maxWidth
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     */
    public function setMaxWidth(?int $maxWidth): \Auraine\BannerSlider\Api\Data\ResourceMapInterface;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @param string $createdAt
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     */
    public function setCreatedAt(string $createdAt): \Auraine\BannerSlider\Api\Data\ResourceMapInterface;

    /**
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * @param string $updatedAt
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     */
    public function setUpdatedAt(string $updatedAt): \Auraine\BannerSlider\Api\Data\ResourceMapInterface;
}