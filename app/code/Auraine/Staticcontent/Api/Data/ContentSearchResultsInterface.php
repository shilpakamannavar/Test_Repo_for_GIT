<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Staticcontent\Api\Data;

interface ContentSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Content list.
     * @return \Auraine\Staticcontent\Api\Data\ContentInterface[]
     */
    public function getItems();

    /**
     * Set type list.
     * @param \Auraine\Staticcontent\Api\Data\ContentInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

