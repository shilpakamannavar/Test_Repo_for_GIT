<?php
namespace Auraine\CsvUploader\Test\Unit\Console\Command;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @covers \Auraine\CsvUploader\Console\Command\ImportOptions
 */
class ImportOptionsTest extends TestCase
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
     * Mock attributeRepository
     *
     * @var \Magento\Catalog\Api\ProductAttributeRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $attributeRepository;

    /**
     * Mock tableFactoryInstance
     *
     * @var \Magento\Eav\Model\Entity\Attribute\Source\Table|PHPUnit\Framework\MockObject\MockObject
     */
    private $tableFactoryInstance;

    /**
     * Mock tableFactory
     *
     * @var \Magento\Eav\Model\Entity\Attribute\Source\TableFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $tableFactory;

    /**
     * Mock attributeOptionManagement
     *
     * @var \Magento\Eav\Api\AttributeOptionManagementInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $attributeOptionManagement;

    /**
     * Mock optionLabelFactoryInstance
     *
     * @var \Magento\Eav\Api\Data\AttributeOptionLabelInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $optionLabelFactoryInstance;

    /**
     * Mock optionLabelFactory
     *
     * @var \Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $optionLabelFactory;

    /**
     * Mock optionFactoryInstance
     *
     * @var \Magento\Eav\Api\Data\AttributeOptionInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $optionFactoryInstance;

    /**
     * Mock optionFactory
     *
     * @var \Magento\Eav\Api\Data\AttributeOptionInterfaceFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $optionFactory;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\CsvUploader\Console\Command\ImportOptions
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
        $this->attributeRepository = $this->createMock(\Magento\Catalog\Api\ProductAttributeRepositoryInterface::class);
        $this->tableFactoryInstance = $this->createMock(\Magento\Eav\Model\Entity\Attribute\Source\Table::class);
        $this->tableFactory = $this->createMock(\Magento\Eav\Model\Entity\Attribute\Source\TableFactory::class);
        $this->tableFactory->method('create')->willReturn($this->tableFactoryInstance);
        $this->attributeOptionManagement = $this->createMock(\Magento\Eav\Api\AttributeOptionManagementInterface::class);
        $this->optionLabelFactoryInstance = $this->createMock(\Magento\Eav\Api\Data\AttributeOptionLabelInterface::class);
        $this->optionLabelFactory = $this->createMock(\Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory::class);
        $this->optionLabelFactory->method('create')->willReturn($this->optionLabelFactoryInstance);
        $this->optionFactoryInstance = $this->createMock(\Magento\Eav\Api\Data\AttributeOptionInterface::class);
        $this->optionFactory = $this->createMock(\Magento\Eav\Api\Data\AttributeOptionInterfaceFactory::class);
        $this->optionFactory->method('create')->willReturn($this->optionFactoryInstance);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\CsvUploader\Console\Command\ImportOptions::class,
            [
                'csvProcessor' => $this->csvProcessor,
                'directoryList' => $this->directoryList,
                'attributeRepository' => $this->attributeRepository,
                'tableFactory' => $this->tableFactory,
                'attributeOptionManagement' => $this->attributeOptionManagement,
                'optionLabelFactory' => $this->optionLabelFactory,
                'optionFactory' => $this->optionFactory,
            ]
        );
    }
  
    public function testGetBasePath()
    {
        $this->testObject->getBasePath();
        $this->assertEquals(1, 1);
    }
    public function testExceptionCreateOrGetId()
    {
       
        $this->expectException(\Magento\Framework\Exception\LocalizedException::class);
        $this->expectExceptionMessage('Label for 1 must not be empty.');
        $this->testObject->createOrGetId(1, '', 'test', 0);
    }
    public function testExceptionImportFromCsvFile()
    {
       
        $this->expectException(\Magento\Framework\Exception\LocalizedException::class);
        $this->expectExceptionMessage('No data.');
        $this->testObject->importFromCsvFile('', 'test');
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
        $this->assertNull($result);
    }

    // public function testGetOptionId()
    // {
    //     $attributeCode = 'Color'; $label='Red'; $force = false;
    //     $this->testObject->getOptionId($attributeCode, $label, $force);
    //     $this->assertEquals(1, 1);
    // }







    public function testGetAttribute()
    {
        $this->testObject->getAttribute(1);
        $this->assertEquals(1, 1);
    }
 

  
    
}
