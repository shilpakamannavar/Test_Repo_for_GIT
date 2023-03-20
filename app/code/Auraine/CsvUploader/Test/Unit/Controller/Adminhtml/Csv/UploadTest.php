<?php
namespace Auraine\CsvUploader\Test\Unit\Controller\Adminhtml\Csv;

use Auraine\CsvUploader\Controller\Adminhtml\Csv\Upload;
use Magento\Backend\App\Action\Context;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;
use Magento\Backend\Model\View\Result\Page as BackendResultPage;

class UploadTest extends TestCase
{
    /** @var Upload */
    private $uploadController;

    /** @var Context|\PHPUnit\Framework\MockObject\MockObject */
    private $contextMock;

    /** @var PageFactory|\PHPUnit\Framework\MockObject\MockObject */
    private $pageFactoryMock;
        /** @var BackendResultPage|\PHPUnit\Framework\MockObject\MockObject */
        private $resultPageMock;


    protected function setUp(): void
    {
        $this->contextMock = $this->getMockBuilder(Context::class)
        ->disableOriginalConstructor()
        ->getMock();
    $this->pageFactoryMock = $this->getMockBuilder(PageFactory::class)
        ->disableOriginalConstructor()
        ->getMock();
    $this->resultPageMock = $this->getMockBuilder(BackendResultPage::class)
        ->disableOriginalConstructor()
        ->getMock();

        $objectManager = new ObjectManager($this);
        $this->uploadController = $objectManager->getObject(Upload::class, [
            'context' => $this->contextMock,
            'resultPageFactory' => $this->pageFactoryMock
        ]);
    }


    public function testExecute()
    {
        $this->assertEquals(1, 1);
    }
}
