<?php
namespace Auraine\CustomImport\Model\Source\Import\Behavior;

use Magento\ImportExport\Model\Import;

/**
 * Custom import behavior for the "pincode" entity.
 */
class Basic extends \Magento\ImportExport\Model\Source\Import\Behavior\Basic
{
    /**
     * Get the options as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            Import::BEHAVIOR_APPEND => __('Add')
        ];
    }

    /**
     * Get the code for the behavior.
     *
     * @return string
     */
    public function getCode()
    {
        return 'pincode'; // add entity name
    }
}
