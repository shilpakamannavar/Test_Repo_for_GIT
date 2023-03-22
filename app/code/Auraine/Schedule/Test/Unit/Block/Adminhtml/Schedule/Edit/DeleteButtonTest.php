<?php

declare(strict_types=1);

namespace Auraine\Schedule\Test\Unit\Block\Adminhtml\Schedule\Edit;

use Auraine\Schedule\Block\Adminhtml\Schedule\Edit\DeleteButton;
use Auraine\Schedule\Block\Adminhtml\Schedule\Edit\GenericButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\UrlInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Magento\Backend\Block\Widget\Context;

class DeleteButtonTest extends TestCase
{
    public const URL = 'http://example.com/schedule/save';

    /**
     * @var MockObject|Context
     */
    private $context;

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
        $this->context = $this->createMock(Context::class);

        $this->genericButtonMock = $this->getMockBuilder(GenericButton::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlBuilderMock = $this->getMockBuilder(UrlInterface::class)
            ->getMockForAbstractClass();

        $this->context
            ->method('getUrlBuilder')
            ->willReturn($this->urlBuilderMock);

        $this->deleteButton = new DeleteButton(
            $this->context,
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
        $scheduleId = 155;

        $this->genericButtonMock->expects($this->once())
            ->method('getModelId')
            ->willReturn(null);

        $deleteUrl = 'http://localhost/magento2/admin/schedule/delete/schedule_id/1';

        $this->urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with('*/*/delete', ['schedule_id' => $scheduleId])
            ->willReturn($deleteUrl);




        $buttonData = $this->deleteButton->getButtonData();
        $this->assertArrayHasKey('label', $buttonData);
        $this->assertArrayHasKey('class', $buttonData);
        $this->assertArrayHasKey('on_click', $buttonData);
        $this->assertArrayHasKey('sort_order', $buttonData);
        $result = $buttonData['label'];
        $this->assertInstanceOf(Phrase::class, $result);


        $this->assertSame('Delete Schedule', $result->getText());
        $this->assertSame('delete', $buttonData['class']);
        $this->assertArrayHasKey('on_click', $buttonData['on_click']);

        $this->assertSame('deleteConfirm(\'' . __(
            'Are you sure you want to do this?'
        ) . '\', \'' . $deleteUrl . '\')', $buttonData['on_click']);

        $this->assertSame(20, $buttonData['sort_order']);
    }

    public function testGetButtonDataWhenModelIdIsNull(): void
    {
        $this->genericButtonMock->expects($this->once())
            ->method('getModelId')
            ->willReturn(null);

        $this->assertEquals([], $this->deleteButton->getButtonData());
    }
}
