<?php

namespace Auraine\Schedule\Test\Unit\Controller\Adminhtml\Schedule;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Auraine\Schedule\Controller\Adminhtml\Schedule\Delete;
use Auraine\Schedule\Model\Schedule;
use Auraine\Schedule\Test\Unit\Controller\Adminhtml\ScheduleTest;

class DeleteTest extends ScheduleTest
{
    /**
     * @var Delete
     */
    protected $controller;

    /**
     * @var Schedule|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $modelMock;

    /**
     * @var RequestInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestMock;

    /**
     * @var ManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $messageManagerMock;

    /**
     * @var Redirect|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $resultRedirectMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->modelMock = $this->createMock(Schedule::class);
        $this->requestMock = $this->getMockForAbstractClass(RequestInterface::class);
        $this->messageManagerMock = $this->getMockForAbstractClass(ManagerInterface::class);
        $this->resultRedirectMock = $this->createMock(Redirect::class);

        $this->controller = $this->objectManager->getObject(
            Delete::class,
            [
                'context' => $this->contextMock,
                'resultRedirectFactory' => $this->resultRedirectFactoryMock,
                '_objectManager' => $this->objectManager,
            ]
        );
    }

    public function testExecute(): void
    {
        $id = 1;

        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('schedule_id')
            ->willReturn($id);

        $this->modelMock->expects($this->once())
            ->method('load')
            ->with($id);

        $this->modelMock->expects($this->once())
            ->method('delete');

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('You deleted the Schedule.'));

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/');

        $this->assertSame($this->resultRedirectMock, $this->controller->execute());
    }

    public function testExecuteException(): void
    {
        $id = 1;
        $exceptionMessage = 'Exception message';

        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('schedule_id')
            ->willReturn($id);

        $this->modelMock->expects($this->once())
            ->method('load')
            ->with($id);

        $this->modelMock->expects($this->once())
            ->method('delete')
            ->willThrowException(new \Exception($exceptionMessage));

        $this->messageManagerMock->expects($this->once())
            ->method('addErrorMessage')
            ->with($exceptionMessage);

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/edit', ['schedule_id' => $id]);

        $this->assertSame($this->resultRedirectMock, $this->controller->execute());
    }

    public function testExecuteNoId(): void
    {
        $this->requestMock->expects($this->once())->method('getParam')
        ->with('schedule_id')
        ->willReturn(null);

        $this->modelMock->expects($this->never())
        ->method('load');

        $this->modelMock->expects($this->never())
        ->method('delete');

        $this->messageManagerMock->expects($this->once())
        ->method('addErrorMessage')
        ->with(__('We can\'t find a Schedule to delete.'));

        $this->resultRedirectMock->expects($this->once())
        ->method('setPath')
        ->with('*/*/');

        $this->assertSame($this->resultRedirectMock, $this->controller->execute());
    }

    protected function tearDown(): void
    {
        $this->controller = null;
        $this->modelMock = null;
        $this->requestMock = null;
        $this->messageManagerMock = null;
        $this->resultRedirectMock = null;

        parent::tearDown();
    }
}
