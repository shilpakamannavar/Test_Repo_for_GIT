<?php
declare(strict_types=1);

namespace Auraine\Schedule\Block\Adminhtml\Schedule\Edit;

use Magento\Backend\Block\Widget\Context;
use PHPUnit\Framework\TestCase;

class GenericButtonTest extends TestCase
{
    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $contextMock;

    /**
     * @var GenericButton
     */
    protected $button;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);

        $this->button = $this->getMockForAbstractClass(
            GenericButton::class,
            [$this->contextMock]
        );
    }

    public function testGetModelId()
    {
        $requestMock = $this->createMock(\Magento\Framework\App\RequestInterface::class);
        $requestMock->expects($this->once())
            ->method('getParam')
            ->with('schedule_id')
            ->willReturn(123);

        $this->contextMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($requestMock);

        $result = $this->button->getModelId();
        $this->assertEquals(123, $result);
    }

    public function testGetUrl()
    {
        $urlBuilderMock = $this->createMock(\Magento\Framework\UrlInterface::class);
        $urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with('my/route', ['param1' => 'value1'])
            ->willReturn('http://localhost/my/route?param1=value1');

        $this->contextMock->expects($this->once())
            ->method('getUrlBuilder')
            ->willReturn($urlBuilderMock);

        $result = $this->button->getUrl('my/route', ['param1' => 'value1']);
        $this->assertEquals('http://localhost/my/route?param1=value1', $result);
    }
}
