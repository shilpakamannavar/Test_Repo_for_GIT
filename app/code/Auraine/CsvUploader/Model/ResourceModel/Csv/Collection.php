<?php
namespace Auraine\CsvUploader\Model\ResourceModel\Csv;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Auraine\CsvUploader\Model\Csv;
use Auraine\CsvUploader\Model\ResourceModel\Csv as ResourceModelCsv;

// @codeCoverageIgnoreStart
class Collection extends AbstractCollection
{
    /**
     * Fuction construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Csv::class, ResourceModelCsv::class);
    }
}
// @codeCoverageIgnoreEnd
