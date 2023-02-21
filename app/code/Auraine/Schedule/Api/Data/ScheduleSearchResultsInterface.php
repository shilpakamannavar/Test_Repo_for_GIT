<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Schedule\Api\Data;

interface ScheduleSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get schedule list.
     * @return \Auraine\Schedule\Api\Data\ScheduleInterface[]
     */
    public function getItems();

    /**
     * Set entity_id list.
     * @param \Auraine\Schedule\Api\Data\ScheduleInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

