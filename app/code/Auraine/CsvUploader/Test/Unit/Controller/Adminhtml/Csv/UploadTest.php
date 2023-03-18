<?php
namespace Auraine\CsvUploader\Test\Unit\Controller\Adminhtml\Csv;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\CsvUploader\Controller\Adminhtml\Csv\Upload
 */
class UploadTest extends TestCase
{
    /**
     * Mock context
     *
     * @var \Magento\Backend\App\Action\Context|PHPUnit\Framework\MockObject\MockObject
     */
    private $context;

    /**
     * Mock resultPageFactoryInstance
     *
     * @var \Magento\Framework\View\Result\Page|PHPUnit\Framework\MockObject\MockObject
     */
    private $resultPageFactoryInstance;

    /**
     * Mock resultPageFactory
     *
     * @var \Magento\Framework\View\Result\PageFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $resultPageFactory;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\CsvUploader\Controller\Adminhtml\Csv\Upload
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->context = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->resultPageFactoryInstance = $this->createMock(\Magento\Framework\View\Result\Page::class);
        $this->resultPageFactory = $this->createMock(\Magento\Framework\View\Result\PageFactory::class);
        $this->resultPageFactory->method('create')->willReturn($this->resultPageFactoryInstance);
        $this->testObject = $this->objectManager->getObject(
        \Auraine\CsvUploader\Controller\Adminhtml\Csv\Upload::class,
            [
                'context' => $this->context,
                'resultPageFactory' => $this->resultPageFactory,
            ]
        );
    }

    public function testExecute()
    {
        // Instantiate the controller
        $context = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $resultPageFactory = $this->createMock(\Magento\Framework\View\Result\PageFactory::class);
        $controller = new \Auraine\CsvUploader\Controller\Adminhtml\Csv\Upload($context, $resultPageFactory);
    
        // Mock the result page object
        $resultPage = $this->createMock(\Magento\Backend\Model\View\Result\Page::class);
        $resultPage->expects($this->once())
            ->method('setActiveMenu')
            ->with('Auraine_CsvUploader::csv_uploader');
     
        $resultPage->getConfig()->getTitle()->expects($this->once())
            ->method('prepend')
            ->with(__('Bulk Upload Size And Color Attribute'));
    
        // Set the result page factory to return the mock result page
        $resultPageFactory->expects($this->once())
            ->method('create')
            ->willReturn($resultPage);
    
        // Call the execute method and assert that it returns the result page
        $this->assertEquals($resultPage, $controller->execute());
    }
    public function testIsAllowed()
{
    // Instantiate the controller
    $context = $this->createMock(\Magento\Backend\App\Action\Context::class);
    $resultPageFactory = $this->createMock(\Magento\Framework\View\Result\PageFactory::class);
    $controller = new \Auraine\CsvUploader\Controller\Adminhtml\Csv\Upload($context, $resultPageFactory);

    // Mock the authorization object
    $authorization = $this->createMock(\Magento\Framework\AuthorizationInterface::class);
    $authorization->expects($this->once())
        ->method('isAllowed')
        ->with('Auraine_CsvUploader::csv_upload')
        ->willReturn(true);

    // Set the authorization object on the controller
    $controller->_authorization = $authorization;

    // Call the _isAllowed() method and assert that it returns true
    $this->assertTrue($controller->_isAllowed());
}
}
