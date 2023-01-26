<?php

declare(strict_types=1);

namespace Auraine\BannerSlider\Api;

interface SliderRepositoryInterface
{
    /**
     * @param int $id
     * @param bool $loadFromCache
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function loadById(int $id, bool $loadFromCache = true): \Auraine\BannerSlider\Api\Data\SliderInterface;

    /**
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function create(): \Auraine\BannerSlider\Api\Data\SliderInterface;

    /**
     * @param \Auraine\BannerSlider\Api\Data\SliderInterface $slider
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Auraine\BannerSlider\Api\Data\SliderInterface $slider): \Auraine\BannerSlider\Api\Data\SliderInterface;

    /**
     * @param \Auraine\BannerSlider\Api\Data\SliderInterface $slider
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Auraine\BannerSlider\Api\Data\SliderInterface $slider): bool;

    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById(int $id): bool;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Auraine\BannerSlider\Api\Data\SliderSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @return \Auraine\BannerSlider\Model\ResourceModel\Slider\Collection
     */
    public function getCollection(): \Auraine\BannerSlider\Model\ResourceModel\Slider\Collection;
}