<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Staticcontent\Api\Data;

interface TypeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Type list.
     * @return \Auraine\Staticcontent\Api\Data\TypeInterface[]
     */
    public function getItems();

    /**
     * Set type list.
     * @param \Auraine\Staticcontent\Api\Data\TypeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

