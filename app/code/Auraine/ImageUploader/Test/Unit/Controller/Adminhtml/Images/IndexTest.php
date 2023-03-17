<?php
namespace Auraine\ImageUploader\Test\Unit\Controller\Adminhtml\Images;

use Auraine\ImageUploader\Controller\Adminhtml\Images\Index;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    /**
     * @var Index
     */
    protected $controller;

    /**
     * @var PageFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $resultPageFactoryMock;

    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $contextMock;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultPageFactoryMock = $this->getMockBuilder(PageFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller = $objectManager->getObject(
            Index::class,
            [
                'context' => $this->contextMock,
                'resultPageFactory' => $this->resultPageFactoryMock
            ]
        );
    }

    /**
     * Test class instantiation
     */
    public function testInstance()
    {
        $this->assertInstanceOf(Index::class, $this->controller);
    }

    /**
     * Test execute method
     */
    public function testExecute()
    {
        $resultPageMock = $this->getMockBuilder(Page::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultPageMock);

        $resultPageMock->expects($this->once())
            ->method('setActiveMenu')
            ->with('Auraine_ImageUploader::images_uploader')
            ->willReturnSelf();

        $configMock = $this->getMockBuilder(\Magento\Framework\View\Page\Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resultPageMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($configMock);

        $titleMock = $this->getMockBuilder(\Magento\Framework\View\Page\Title::class)
            ->disableOriginalConstructor()
            ->getMock();

        $configMock->expects($this->once())
            ->method('getTitle')
            ->willReturn($titleMock);

        $titleMock->expects($this->once())
            ->method('prepend')
            ->with(__('Images'))
            ->willReturnSelf();

        $this->assertSame($resultPageMock, $this->controller->execute());
    }
}
