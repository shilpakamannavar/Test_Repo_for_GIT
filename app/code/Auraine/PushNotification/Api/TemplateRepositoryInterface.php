<?php

declare(strict_types=1);

namespace Auraine\PushNotification\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface TemplateRepositoryInterface
{

    /**
     * Save Template
     * @param \Auraine\PushNotification\Api\Data\TemplateInterface $template
     * @return \Auraine\PushNotification\Api\Data\TemplateInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Auraine\PushNotification\Api\Data\TemplateInterface $template
    );

    /**
     * Retrieve Template
     * @param string $templateId
     * @return \Auraine\PushNotification\Api\Data\TemplateInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($templateId);

    /**
     * Retrieve Template matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Auraine\PushNotification\Api\Data\TemplateSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Template
     * @param \Auraine\PushNotification\Api\Data\TemplateInterface $template
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Auraine\PushNotification\Api\Data\TemplateInterface $template
    );

    /**
     * Delete Template by ID
     * @param string $templateId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($templateId);
}

