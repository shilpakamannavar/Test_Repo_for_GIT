<?php

declare(strict_types=1);

namespace Auraine\BannerSlider\Api\Data;

interface ResourceMapInterface
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
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     */
    public function setEntityId($entityId);

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
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     */
    public function setTitle(string $title): \Auraine\BannerSlider\Api\Data\ResourceMapInterface;

    /**
     * Get Min Width
     *
     * @return int|null
     */
    public function getMinWidth(): ?int;

    /**
     * Set Min Width
     *
     * @param int|null $minWidth
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     */
    public function setMinWidth(?int $minWidth): \Auraine\BannerSlider\Api\Data\ResourceMapInterface;

    /**
     * Get Max Width
     *
     * @return int|null
     */
    public function getMaxWidth(): ?int;

    /**
     * Set Max Width
     *
     * @param int|null $maxWidth
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     */
    public function setMaxWidth(?int $maxWidth): \Auraine\BannerSlider\Api\Data\ResourceMapInterface;

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
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     */
    public function setCreatedAt(string $createdAt): \Auraine\BannerSlider\Api\Data\ResourceMapInterface;

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
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     */
    public function setUpdatedAt(string $updatedAt): \Auraine\BannerSlider\Api\Data\ResourceMapInterface;
}
