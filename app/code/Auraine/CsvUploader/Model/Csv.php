<?php
namespace Auraine\CsvUploader\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Auraine\CsvUploader\Api\Data\CsvInterface;
use Auraine\CsvUploader\Model\ResourceModel\Csv as ResourceModelCsv;

class Csv extends AbstractModel implements CsvInterface, IdentityInterface
{
    
    public const CACHE_TAG = 'Auraine_csv';
    
    /**
     * Get identites
     *
     * @return id
     */
    public function getIdentities()
    {
        return [
            self::CACHE_TAG . '_' . $this->getId(),
        ];
    }

    /**
     * Function Construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModelCsv::class);
    }

    /**
     * Get the path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getData(self::PATH);
    }

    /**
     * Set path
     *
     * @param string $value
     * @return string
     */
    public function setPath($value)
    {
        return $this->setData(self::PATH, $value);
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }
    /**
     * Set name
     *
     * @param string $value
     * @return string
     */
    public function setName($value)
    {
        return $this->setData(self::NAME, $value);
    }
}
