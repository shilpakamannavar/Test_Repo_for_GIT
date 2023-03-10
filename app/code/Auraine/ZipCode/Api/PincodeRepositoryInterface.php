<?php
declare(strict_types=1);

namespace Auraine\ZipCode\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface PincodeRepositoryInterface
{

    /**
     * Save pincode
     *
     * @param \Auraine\ZipCode\Api\Data\PincodeInterface $pincode
     * @return \Auraine\ZipCode\Api\Data\PincodeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Auraine\ZipCode\Api\Data\PincodeInterface $pincode
    );

    /**
     * Retrieve pincode
     *
     * @param string $pincodeId
     * @return \Auraine\ZipCode\Api\Data\PincodeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($pincodeId);

    /**
     * Retrieve pincode matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Auraine\ZipCode\Api\Data\PincodeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete pincode
     *
     * @param \Auraine\ZipCode\Api\Data\PincodeInterface $pincode
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Auraine\ZipCode\Api\Data\PincodeInterface $pincode
    );

    /**
     * Delete pincode by ID
     *
     * @param string $pincodeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($pincodeId);
}
