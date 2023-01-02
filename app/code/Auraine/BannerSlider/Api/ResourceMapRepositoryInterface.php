<?php

declare(strict_types=1);

namespace Auraine\BannerSlider\Api;

interface ResourceMapRepositoryInterface
{
    /**
     * @param int $id
     * @param bool $loadFromCache
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function loadById(int $id, bool $loadFromCache = true): \Auraine\BannerSlider\Api\Data\ResourceMapInterface;

    /**
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     */
    public function create(): \Auraine\BannerSlider\Api\Data\ResourceMapInterface;
    
    /**
     * @param \Auraine\BannerSlider\Api\Data\ResourceMapInterface $resourceMap
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Auraine\BannerSlider\Api\Data\ResourceMapInterface $resourceMap): \Auraine\BannerSlider\Api\Data\ResourceMapInterface;

    /**
     * @param \Auraine\BannerSlider\Api\Data\ResourceMapInterface $resourceMap
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Auraine\BannerSlider\Api\Data\ResourceMapInterface $resourceMap): bool;

    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById(int $id): bool;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @return \Auraine\BannerSlider\Model\ResourceModel\ResourceMap\Collection
     */
    public function getCollection(): \Auraine\BannerSlider\Model\ResourceModel\ResourceMap\Collection;
}