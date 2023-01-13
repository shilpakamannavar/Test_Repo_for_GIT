<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Staticcontent\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface TypeRepositoryInterface
{

    /**
     * Save Type
     * @param \Auraine\Staticcontent\Api\Data\TypeInterface $type
     * @return \Auraine\Staticcontent\Api\Data\TypeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Auraine\Staticcontent\Api\Data\TypeInterface $type
    );

    /**
     * Retrieve Type
     * @param string $typeId
     * @return \Auraine\Staticcontent\Api\Data\TypeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($typeId);

    /**
     * Retrieve Type matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Auraine\Staticcontent\Api\Data\TypeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Type
     * @param \Auraine\Staticcontent\Api\Data\TypeInterface $type
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Auraine\Staticcontent\Api\Data\TypeInterface $type
    );

    /**
     * Delete Type by ID
     * @param string $typeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($typeId);
}
