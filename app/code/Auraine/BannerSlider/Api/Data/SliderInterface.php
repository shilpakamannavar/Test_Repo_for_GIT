<?php

declare(strict_types=1);

namespace Auraine\BannerSlider\Api\Data;


interface SliderInterface
{
    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $entityId
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function setEntityId($entityId);

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @param string $title
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function setTitle(string $title): \Auraine\BannerSlider\Api\Data\SliderInterface;

    /**
     * @return int
     */
    public function getIsShowTitle(): int;

    /**
     * @param int $isShowTitle
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function setIsShowTitle(int $isShowTitle): \Auraine\BannerSlider\Api\Data\SliderInterface;

    /**
     * @return int
     */
    public function getIsEnabled(): int;

    /**
     * @param int $isEnabled
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function setIsEnabled(int $isEnabled): \Auraine\BannerSlider\Api\Data\SliderInterface;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @param string $createdAt
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function setCreatedAt(string $createdAt): \Auraine\BannerSlider\Api\Data\SliderInterface;

    /**
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * @param string $updatedAt
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function setUpdatedAt(string $updatedAt): \Auraine\BannerSlider\Api\Data\SliderInterface;

    /**
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface[]
     */
    public function getBanners(): array;
}