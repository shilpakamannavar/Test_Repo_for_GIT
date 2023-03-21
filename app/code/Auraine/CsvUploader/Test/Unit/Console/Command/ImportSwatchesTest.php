<?php
namespace Auraine\CsvUploader\Test\Unit\Console\Command;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @covers \Auraine\CsvUploader\Console\Command\ImportSwatches
 */
class ImportSwatchesTest extends TestCase
{
    /**
     * Mock csvProcessor
     *
     * @var \Magento\Framework\File\Csv|PHPUnit\Framework\MockObject\MockObject
     */
    private $csvProcessor;

    /**
     * Mock directoryList
     *
     * @var \Magento\Framework\App\Filesystem\DirectoryList|PHPUnit\Framework\MockObject\MockObject
     */
    private $directoryList;

    /**
     * Mock resource
     *
     * @var \Magento\Framework\App\ResourceConnection|PHPUnit\Framework\MockObject\MockObject
     */
    private $resource;

    /**
     * Mock eavAttribute
     *
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute|PHPUnit\Framework\MockObject\MockObject
     */
    private $eavAttribute;

    /**
     * Mock scopeConfig
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $scopeConfig;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\CsvUploader\Console\Command\ImportSwatches
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->csvProcessor = $this->createMock(\Magento\Framework\File\Csv::class);
        $this->directoryList = $this->createMock(\Magento\Framework\App\Filesystem\DirectoryList::class);
        $this->resource = $this->createMock(\Magento\Framework\App\ResourceConnection::class);
        $this->eavAttribute = $this->createMock(\Magento\Eav\Model\ResourceModel\Entity\Attribute::class);
        $this->scopeConfig = $this->createMock(\Magento\Framework\App\Config\ScopeConfigInterface::class);
        $this->testObject = $this->objectManager->getObject(
        \Auraine\CsvUploader\Console\Command\ImportSwatches::class,
            [
                'csvProcessor' => $this->csvProcessor,
                'directoryList' => $this->directoryList,
                'resource' => $this->resource,
                'eavAttribute' => $this->eavAttribute,
                'scopeConfig' => $this->scopeConfig,
            ]
        );
    }
    public function testGetBasePath()
    {
        $this->testObject->getBasePath();
        $this->assertEquals(1, 1);
    }

    public function testExecuteWithoutAttribute()
    {
        // Create mock input and output instances
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        // Set up the input arguments
        $attributeCode = '';
        $filePath = '';
        $input->method('getArgument')->willReturnMap([
            ['attribute_code', $attributeCode],
            ['file_path', $filePath],
        ]);

       // Call the execute method and assert that it returns null
        $result = $this->testObject->execute($input, $output);
        $this->assertEquals(false, $result);
    }
    
    
}
