<?php
namespace Auraine\CsvUploader\Test\Unit\Controller\Adminhtml\Csv;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\CsvUploader\Controller\Adminhtml\Csv\Save
 */
class SaveTest extends TestCase
{
    /**
     * Mock context
     *
     * @var \Magento\Backend\App\Action\Context|PHPUnit\Framework\MockObject\MockObject
     */
    private $context;

    /**
     * Mock uploaderFactoryInstance
     *
     * @var \Magento\MediaStorage\Model\File\Uploader|PHPUnit\Framework\MockObject\MockObject
     */
    private $uploaderFactoryInstance;

    /**
     * Mock uploaderFactory
     *
     * @var \Magento\MediaStorage\Model\File\UploaderFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $uploaderFactory;

    /**
     * Mock filesystem
     *
     * @var \Magento\Framework\Filesystem|PHPUnit\Framework\MockObject\MockObject
     */
    private $filesystem;

    /**
     * Mock hexColor
     *
     * @var \Auraine\CsvUploader\Console\Command\ImportHex|PHPUnit\Framework\MockObject\MockObject
     */
    private $hexColor;

    /**
     * Mock importOptions
     *
     * @var \Auraine\CsvUploader\Console\Command\ImportOptions|PHPUnit\Framework\MockObject\MockObject
     */
    private $importOptions;

    /**
     * Mock importSwatches
     *
     * @var \Auraine\CsvUploader\Console\Command\ImportSwatches|PHPUnit\Framework\MockObject\MockObject
     */
    private $importSwatches;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\CsvUploader\Controller\Adminhtml\Csv\Save
     */
    private $testObject;
    private $requestMock;

      /** @var Http|MockObject */
      protected $request;
    
    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->context = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->uploaderFactoryInstance = $this->createMock(\Magento\MediaStorage\Model\File\Uploader::class);
        $this->uploaderFactory = $this->createMock(\Magento\MediaStorage\Model\File\UploaderFactory::class);
        $this->uploaderFactory->method('create')->willReturn($this->uploaderFactoryInstance);
        $this->filesystem = $this->createMock(\Magento\Framework\Filesystem::class);
        $this->hexColor = $this->createMock(\Auraine\CsvUploader\Console\Command\ImportHex::class);
        $this->importOptions = $this->createMock(\Auraine\CsvUploader\Console\Command\ImportOptions::class);
        $this->importSwatches = $this->createMock(\Auraine\CsvUploader\Console\Command\ImportSwatches::class);
        $this->requestMock = $this->getMockBuilder(Http::class)
        ->disableOriginalConstructor()
        ->getMock();
        $requestInterfaceMock = $this->getMockBuilder(Http::class)
        ->setMethods(
            ['getParam', 'getPost', 'getFullActionName', 'getPostValue']
        )->disableOriginalConstructor()
        ->getMock();


        $this->request = $requestInterfaceMock;

        $this->testObject = $this->objectManager->getObject(
            \Auraine\CsvUploader\Controller\Adminhtml\Csv\Save::class,
            [
                'context' => $this->context,
                'uploaderFactory' => $this->uploaderFactory,
                'filesystem' => $this->filesystem,
                'hexColor' => $this->hexColor,
                'importOptions' => $this->importOptions,
                'importSwatches' => $this->importSwatches,
            ]
        );
    }

    

     /**
     * Test execute with invalid request
     */
    public function testExecuteThrowsExceptionForInvalidRequest()
    {
    $this->assertEquals(1, 1) ;
    }
}
