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
     * @return array
     */
    public function dataProviderForTestExecute()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestExecute
     */
    public function testExecute(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestDispatch()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestDispatch
     */
    public function testDispatch(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTest_processUrlKeys()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTest_processUrlKeys
     */
    public function test_processUrlKeys(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetUrl()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetUrl
     */
    public function testGetUrl(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetActionFlag()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetActionFlag
     */
    public function testGetActionFlag(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetRequest()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetRequest
     */
    public function testGetRequest(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetResponse()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetResponse
     */
    public function testGetResponse(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
