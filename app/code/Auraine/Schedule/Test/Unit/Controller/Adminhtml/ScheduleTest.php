<?php

namespace Auraine\Schedule\Test\Unit\Controller\Adminhtml;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class ScheduleTest extends TestCase
{
    protected $objectManager;
    protected $context;
    protected $coreRegistry;
    protected $schedule;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        $this->context = $this->createMock(Context::class);
        $this->coreRegistry = $this->createMock(Registry::class);

        $this->schedule = $this->objectManager->getObject(
            \Auraine\Schedule\Controller\Adminhtml\Schedule::class,
            [
                'context' => $this->context,
                'coreRegistry' => $this->coreRegistry,
            ]
        );
    }

    public function testInitPage()
    {
        $resultPage = $this->createMock(\Magento\Backend\Model\View\Result\Page::class);
        $resultPage->expects($this->once())
            ->method('setActiveMenu')
            ->with(\Auraine\Schedule\Controller\Adminhtml\Schedule::ADMIN_RESOURCE)
            ->willReturnSelf();
        $resultPage->expects($this->once())
            ->method('addBreadcrumb')
            ->with(__('Auraine'), __('Auraine'))
            ->willReturnSelf();
        $resultPage->expects($this->once())
            ->method('addBreadcrumb')
            ->with(__('Schedule'), __('Schedule'))
            ->willReturnSelf();
         
            $this->assertEquals($this->schedule, $this->schedule->initPage($resultPage));
    }
}
