<?php

declare(strict_types=1);

namespace Auraine\BannerSlider\Api\Data;

interface SliderInterface
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
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function setEntityId($entityId);

    /**
     * Get Ttitle
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Set Title
     *
     * @param string $title
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function setTitle(string $title): \Auraine\BannerSlider\Api\Data\SliderInterface;

    /**
     * Get Show Ttitle
     *
     * @return int
     */
    public function getIsShowTitle(): int;

    /**
     * Set Show Ttitle
     *
     * @param int $isShowTitle
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function setIsShowTitle(int $isShowTitle): \Auraine\BannerSlider\Api\Data\SliderInterface;

    /**
     * Get Enabled
     *
     * @return int
     */
    public function getIsEnabled(): int;

    /**
     * Set Enabled
     *
     * @param int $isEnabled
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function setIsEnabled(int $isEnabled): \Auraine\BannerSlider\Api\Data\SliderInterface;

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
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function setCreatedAt(string $createdAt): \Auraine\BannerSlider\Api\Data\SliderInterface;

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
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function setUpdatedAt(string $updatedAt): \Auraine\BannerSlider\Api\Data\SliderInterface;

    /**
     * Get Banner
     *
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface[]
     */
    public function getBanners(): array;
}
