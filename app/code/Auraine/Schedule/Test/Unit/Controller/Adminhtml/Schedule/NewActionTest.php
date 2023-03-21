<?php

namespace Auraine\Schedule\Test\Unit\Controller\Adminhtml\Schedule;

use Auraine\Schedule\Controller\Adminhtml\Schedule\NewAction;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Forward;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Controller\Result\ForwardFactory as ResultForwardFactory;
use Magento\Framework\Controller\Result\Forward as ResultForward;
use Magento\Framework\Registry;

class NewActionTest extends \PHPUnit\Framework\TestCase
{
    protected $contextMock;
    protected $coreRegistryMock;
    protected $resultForwardFactoryMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->coreRegistryMock = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->resultForwardMock = $this->getMockBuilder(ResultForward::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->resultForwardFactoryMock = $this->getMockBuilder(ForwardFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
    }

    public function testExecute()
    {
        $newAction = new NewAction(
            $this->contextMock,
            $this->coreRegistryMock,
            $this->resultForwardFactoryMock
        );

        $this->resultForwardFactoryMock->expects($this->once())
        ->method('create')
        ->willReturn($this->resultForwardMock);

        $this->assertInstanceOf(Forward::class, $newAction->execute());
    }
}
