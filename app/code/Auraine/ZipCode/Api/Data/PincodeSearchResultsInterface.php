<?php
declare(strict_types=1);

namespace Auraine\ZipCode\Api\Data;

interface PincodeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get pincode list.
     * @return \Auraine\ZipCode\Api\Data\PincodeInterface[]
     */
    public function getItems();

    /**
     * Set code list.
     * @param \Auraine\ZipCode\Api\Data\PincodeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

