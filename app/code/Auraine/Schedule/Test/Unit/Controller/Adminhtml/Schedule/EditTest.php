<?php

namespace Auraine\Schedule\Test\Unit\Controller\Adminhtml\Schedule;

use Auraine\Schedule\Controller\Adminhtml\Schedule\Edit;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;

class EditTest extends TestCase
{
    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    private $contextMock;

    /**
     * @var Registry|\PHPUnit\Framework\MockObject\MockObject
     */
    private $coreRegistryMock;

    /**
     * @var PageFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $resultPageFactoryMock;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Edit
     */
    private $controller;

    protected function setUp(): void
    {
        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->coreRegistryMock = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultPageFactoryMock = $this->getMockBuilder(PageFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManager = new ObjectManager($this);

        $this->controller = $this->objectManager->getObject(
            Edit::class,
            [
                'context' => $this->contextMock,
                'coreRegistry' => $this->coreRegistryMock,
                'resultPageFactory' => $this->resultPageFactoryMock,
            ]
        );
    }

    /**
     * @dataProvider executeDataProvider
     */
    public function testExecute($scheduleId, $modelId, $isAllowed)
    {
        $requestMock = $this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->getMock();
        $requestMock->expects($this->once())
            ->method('getParam')
            ->with('schedule_id')
            ->willReturn($scheduleId);

        $modelMock = $this->getMockBuilder(\Auraine\Schedule\Model\Schedule::class)
            ->disableOriginalConstructor()
            ->getMock();
        $modelMock->expects($this->once())
            ->method('load')
            ->with($modelId)
            ->willReturnSelf();
        $modelMock->expects($this->once())
            ->method('getId')
            ->willReturn($modelId);

        $this->contextMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($requestMock);
        $this->coreRegistryMock->expects($this->once())
            ->method('register')
            ->with('auraine_schedule_schedule', $modelMock);
        $this->resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->getMockBuilder(Page::class)
                ->disableOriginalConstructor()
                ->getMock());

        $authorizationMock = $this->getMockBuilder(\Magento\Framework\AuthorizationInterface::class)
            ->getMock();
        $authorizationMock->expects($this->once())
        ->method('isAllowed')
        ->with('Auraine_Schedule::schedule_edit')
        ->willReturn($isAllowed);

        $this->contextMock->expects($this->once())
        ->method('getAuthorization')
        ->willReturn($authorizationMock);

        $result = $this->controller->execute();

        $this->assertInstanceOf(Page::class, $result);
    }

    public function executeDataProvider()
    {
        return [
        'authorized' => [
            'scheduleId' => 1,
            'modelId' => 1,
            'isAllowed' => true,
        ],
        'not authorized' => [
            'scheduleId' => 2,
            'modelId' => 2,
            'isAllowed' => false,
        ],
        ];
    }
}
