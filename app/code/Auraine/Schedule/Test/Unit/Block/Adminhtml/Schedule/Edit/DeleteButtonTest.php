<?php

declare(strict_types=1);

namespace Auraine\Schedule\Test\Unit\Block\Adminhtml\Schedule\Edit;

use Auraine\Schedule\Block\Adminhtml\Schedule\Edit\DeleteButton;
use Auraine\Schedule\Block\Adminhtml\Schedule\Edit\GenericButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\UrlInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class DeleteButtonTest extends TestCase
{
    /**
     * @var DeleteButton
     */
    protected $deleteButton;

    /**
     * @var GenericButton|MockObject
     */
    protected $genericButtonMock;

    /**
     * @var UrlInterface|MockObject
     */
    protected $urlBuilderMock;

    protected function setUp(): void
    {
        $this->genericButtonMock = $this->getMockBuilder(GenericButton::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlBuilderMock = $this->getMockBuilder(UrlInterface::class)
            ->getMockForAbstractClass();

        $this->deleteButton = new DeleteButton(
            $this->genericButtonMock,
            $this->urlBuilderMock
        );
    }

    public function testInstanceOfButtonProviderInterface(): void
    {
        $this->assertInstanceOf(ButtonProviderInterface::class, $this->deleteButton);
    }

    public function testGetButtonDataWhenModelIdIsNotNull(): void
    {
        $scheduleId = 1;

        $this->genericButtonMock->expects($this->once())
            ->method('getModelId')
            ->willReturn($scheduleId);

        $deleteUrl = 'http://localhost/magento2/admin/schedule/delete/schedule_id/1';
        $this->urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with('*/*/delete', ['schedule_id' => $scheduleId])
            ->willReturn($deleteUrl);

        $expectedResult = [
            'label' => __('Delete Schedule'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm(\'' . __(
                'Are you sure you want to do this?'
            ) . '\', \'' . $deleteUrl . '\')',
            'sort_order' => 20,
        ];

        $this->assertEquals($expectedResult, $this->deleteButton->getButtonData());
    }

    public function testGetButtonDataWhenModelIdIsNull(): void
    {
        $this->genericButtonMock->expects($this->once())
            ->method('getModelId')
            ->willReturn(null);

        $this->assertEquals([], $this->deleteButton->getButtonData());
    }
}
