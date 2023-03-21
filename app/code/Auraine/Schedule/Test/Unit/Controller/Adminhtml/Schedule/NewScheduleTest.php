<?php

namespace Auraine\Schedule\Controller\Adminhtml\Schedule;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class NewScheduleTest extends TestCase
{
    /**
     * @var NewSchedule
     */
    protected $newSchedule;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->newSchedule = $objectManager->getObject(NewSchedule::class, [
            'context' => $objectManager->getObject(\Magento\Backend\App\Action\Context::class),
            'pageFactory' => $objectManager->getObject(\Magento\Framework\View\Result\PageFactory::class)
        ]);
    }

    public function testExecute()
    {
        // Replace the cronjob class with a mock that returns a value for the test
        $cronjobMock = $this->getMockBuilder(\Auraine\Schedule\Cron\BannerSchedular::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cronjobMock->expects($this->once())->method('execute')->willReturn('test result');

        // Replace the object manager with a mock that returns the cronjob mock
        $objectManagerMock = $this->getMockBuilder(\Magento\Framework\App\ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $objectManagerMock->expects($this->once())
            ->method('create')
            ->with(\Auraine\Schedule\Cron\BannerSchedular::class)
            ->willReturn($cronjobMock);

        // Replace the object manager instance in the newSchedule object with the mock
        $reflection = new \ReflectionClass($this->newSchedule);
        $property = $reflection->getProperty('_objectManager');
        $property->setAccessible(true);
        $property->setValue($this->newSchedule, $objectManagerMock);

        // Call the execute method and verify the result
        $resultRedirectMock = $this->getMockBuilder(\Magento\Framework\Controller\Result\Redirect::class)
            ->disableOriginalConstructor()
            ->getMock();
        $resultRedirectMock->expects($this->once())->method('setPath')->with('*/*/');
        $responseMock = $this->getMockBuilder(\Magento\Framework\App\ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->newSchedule->expects($this->once())
            ->method('_getResponse')->willReturn($responseMock);
        $this->newSchedule->expects($this->once())
            ->method('getResultRedirectFactory')->willReturn(
                $this->getMockBuilder(\Magento\Framework\Controller\Result\RedirectFactory::class)
                    ->disableOriginalConstructor()
                    ->getMock()
                    ->expects($this->once())
                    ->method('create')
                    ->willReturn($resultRedirectMock)
            );
         $this->assertSame($resultRedirectMock, $this->newSchedule->execute());
    }
}
