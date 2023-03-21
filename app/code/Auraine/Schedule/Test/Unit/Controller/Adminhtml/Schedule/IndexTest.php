<?php

/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Auraine\Schedule\Test\Unit\Controller\Adminhtml\Schedule;

use Auraine\Schedule\Controller\Adminhtml\Schedule\Index;
use Magento\Backend\App\Action\Context;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    private $contextMock;

    /**
     * @var PageFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $resultPageFactoryMock;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Index
     */
    private $controller;

    protected function setUp(): void
    {
        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultPageFactoryMock = $this->getMockBuilder(PageFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManager = new ObjectManager($this);

        $this->controller = $this->objectManager->getObject(
            Index::class,
            [
                'context' => $this->contextMock,
                'resultPageFactory' => $this->resultPageFactoryMock,
            ]
        );
    }

    public function testExecute()
    {
        $resultPageMock = $this->getMockBuilder(Page::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultPageMock);

        $resultPageMock->expects($this->once())
            ->method('getConfig')
            ->willReturnSelf();

        $resultPageMock->expects($this->once())
            ->method('getTitle')
            ->willReturnSelf();

        $resultPageMock->expects($this->once())
            ->method('prepend')
            ->with(__('Manage Schedule'))
            ->willReturnSelf();

        $result = $this->controller->execute();

        $this->assertInstanceOf(Page::class, $result);
    }

    public function testIsAllowed()
    {
        $authorizationMock = $this->getMockBuilder(\Magento\Framework\AuthorizationInterface::class)
            ->getMock();

        $authorizationMock->expects($this->once())
            ->method('isAllowed')
            ->with('Auraine_Schedule::schedule_index')
            ->willReturn(true);

        $this->contextMock->expects($this->once())
            ->method('getAuthorization')
            ->willReturn($authorizationMock);

        $this->assertTrue($this->controller->_isAllowed());
    }
}
