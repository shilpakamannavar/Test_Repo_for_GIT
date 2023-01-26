<?php

declare(strict_types=1);

namespace Auraine\BannerSlider\Api;

interface BannerRepositoryInterface
{
    /**
     * @param int $id
     * @param bool $loadFromCache
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function loadById(int $id, bool $loadFromCache = true): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function create(): \Auraine\BannerSlider\Api\Data\BannerInterface;
    
    /**
     * @param \Auraine\BannerSlider\Api\Data\BannerInterface $banner
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Auraine\BannerSlider\Api\Data\BannerInterface $banner): \Auraine\BannerSlider\Api\Data\BannerInterface;

    /**
     * @param \Auraine\BannerSlider\Api\Data\BannerInterface $banner
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Auraine\BannerSlider\Api\Data\BannerInterface $banner): bool;

    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById(int $id): bool;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Auraine\BannerSlider\Api\Data\BannerSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @return \Auraine\BannerSlider\Model\ResourceModel\Banner\Collection
     */
    public function getCollection(): \Auraine\BannerSlider\Model\ResourceModel\Banner\Collection;
}