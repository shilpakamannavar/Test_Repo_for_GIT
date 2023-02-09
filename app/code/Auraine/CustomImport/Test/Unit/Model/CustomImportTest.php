<?php
namespace Auraine\CustomImport\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Auraine\CustomImport\Model\CustomImport;
use PHPUnit\Framework\TestCase;

class CustomImportTest extends TestCase
{
    /**
     * @var Import
     */
    protected $importModel;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $fileDriver;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;

    /**
     * Set up
     */
    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->fileDriver = $this->getMockBuilder(\Magento\Framework\Filesystem\Driver\File::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->directoryList = $this->getMockBuilder(\Magento\Framework\App\Filesystem\DirectoryList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->csvProcessor = $this->getMockBuilder(\Magento\Framework\File\Csv::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->importModel = $this->objectManager->getObject(
            \Vendor\Module\Model\Import::class,
            [
                'fileDriver' => $this->fileDriver,
                'directoryList' => $this->directoryList,
                'csvProcessor' => $this->csvProcessor
            ]
        );
    }

    /**
     * Test import method
     */
    public function testImport()
    {
        $filePath = 'Files/Sample/pincode.csv';

        $data = [
            [
                'pincode_id',
                'code',
                'city',
                'state',
                'country_id',
                'status'
            ],
            [
                'value_1_1',
                'value_1_2',
                'value_1_3',
                'value_1_4',
                'value_1_5',
                'value_1_6'
            ],
            [
                'value_2_1',
                'value_2_2',
                'value_2_3',
                'value_2_4',
                'value_2_5',
                'value_2_6'
            ]
        ];

        $this->fileDriver->expects($this->once())
            ->method('fileExists')
            ->with($filePath)
            ->willReturn(true);

        $this->csvProcessor->expects($this->once())
            ->method('getData')
            ->with($filePath)
            ->willReturn($data);

        $this->assertEquals($this->importModel->import($filePath), $data);
    }
}
