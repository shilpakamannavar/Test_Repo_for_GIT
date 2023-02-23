<?php

declare(strict_types=1);

namespace Auraine\BannerSlider\Api;

interface SliderRepositoryInterface
{
    /**
     * Slider Load By Id
     *
     * @param int $id
     * @param bool $loadFromCache
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function loadById(int $id, bool $loadFromCache = true): \Auraine\BannerSlider\Api\Data\SliderInterface;

    /**
     * Create Slider
     *
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function create(): \Auraine\BannerSlider\Api\Data\SliderInterface;

    /**
     * Save Slider
     *
     * @param \Auraine\BannerSlider\Api\Data\SliderInterface $slider
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Auraine\BannerSlider\Api\Data\SliderInterface $slider):
    \Auraine\BannerSlider\Api\Data\SliderInterface;

    /**
     * Delete Slider
     *
     * @param \Auraine\BannerSlider\Api\Data\SliderInterface $slider
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Auraine\BannerSlider\Api\Data\SliderInterface $slider): bool;

    /**
     * Delete By Id
     *
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById(int $id): bool;

    /**
     * Get List
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Auraine\BannerSlider\Api\Data\SliderSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Get Collection
     *
     * @return \Auraine\BannerSlider\Model\ResourceModel\Slider\Collection
     */
    public function getCollection(): \Auraine\BannerSlider\Model\ResourceModel\Slider\Collection;
}
