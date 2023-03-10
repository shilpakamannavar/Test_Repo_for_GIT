<?php

namespace Auraine\ImageUploader\Test\Unit\Controller\Adminhtml\Images;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Auraine\ImageUploader\Controller\Adminhtml\Images\Upload;

class UploadTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $context;

    /**
     * @var PageFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $resultPageFactory;

    /**
     * @var Upload
     */
    protected $controller;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultPageFactory = $this->getMockBuilder(PageFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller = $objectManager->getObject(
            Upload::class,
            [
                'context' => $this->context,
                'resultPageFactory' => $this->resultPageFactory
            ]
        );
    }

    public function testExecute()
    {
        $resultPage = $this->getMockBuilder(\Magento\Backend\Model\View\Result\Page::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resultPage->expects($this->once())
            ->method('setActiveMenu')
            ->with('Auraine_ImageUploader::images_uploader')
            ->willReturnSelf();

        $title = $this->getMockBuilder(\Magento\Framework\View\Page\Title::class)
            ->disableOriginalConstructor()
            ->getMock();

        $title->expects($this->once())
            ->method('prepend')
            ->with(__('Upload Image'))
            ->willReturnSelf();

        $config = $this->getMockBuilder(\Magento\Framework\View\Page\Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $config->expects($this->once())
            ->method('getTitle')
            ->willReturn($title);

        $resultPage->expects($this->once())
            ->method('getConfig')
            ->willReturn($config);

        $this->resultPageFactory->expects($this->once())
            ->method('create')
            ->willReturn($resultPage);

        $this->assertEquals($resultPage, $this->controller->execute());
    }
}
