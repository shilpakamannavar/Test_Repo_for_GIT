<?php
namespace Auraine\CsvUploader\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Csv extends AbstractDb
{

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        return $this->_init('Auraine_csv', 'csv_id');
    }
}
