<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Staticcontent\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ContentRepositoryInterface
{

    /**
     * Save Content
     * @param \Auraine\Staticcontent\Api\Data\ContentInterface $content
     * @return \Auraine\Staticcontent\Api\Data\ContentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Auraine\Staticcontent\Api\Data\ContentInterface $content
    );

    /**
     * Retrieve Content
     * @param string $contentId
     * @return \Auraine\Staticcontent\Api\Data\ContentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($contentId);

    /**
     * Retrieve Content matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Auraine\Staticcontent\Api\Data\ContentSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Content
     * @param \Auraine\Staticcontent\Api\Data\ContentInterface $content
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Auraine\Staticcontent\Api\Data\ContentInterface $content
    );

    /**
     * Delete Content by ID
     * @param string $contentId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($contentId);
}

