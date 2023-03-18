<?php
namespace Auraine\CsvUploader\Test\Unit\Model;

use Auraine\CsvUploader\Model\Csv;
use Auraine\CsvUploader\Model\ResourceModel\Csv as ResourceModelCsv;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class CsvTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var Csv
     */
    protected $csv;

    /**
     * Set up test case
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        $this->csv = $this->objectManager->getObject(
            Csv::class,
            [
                'resource' => $this->objectManager->getObject(ResourceModelCsv::class),
            ]
        );
    }

    /**
     * Test getIdentities() method
     *
     * @return void
     */
    public function testGetIdentities()
    {
        $this->csv->setId(1);
        $this->assertSame(['Auraine_csv_1'], $this->csv->getIdentities());
    }

    /**
     * Test getPath() and setPath() methods
     *
     * @return void
     */
    public function testGetSetPath()
    {
        $path = '/path/to/csv';
        $this->csv->setPath($path);
        $this->assertSame($path, $this->csv->getPath());
    }

    /**
     * Test getName() and setName() methods
     *
     * @return void
     */
    public function testGetSetName()
    {
        $name = 'test.csv';
        $this->csv->setName($name);
        $this->assertSame($name, $this->csv->getName());
    }
}