<?php
declare(strict_types=1);

namespace Auraine\Schedule\Test\Unit\Block\Adminhtml\Schedule\Edit;

use Auraine\Schedule\Block\Adminhtml\Schedule\Edit\BackButton;
use Auraine\Schedule\Block\Adminhtml\Schedule\Edit\GenericButton;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\UrlInterface;
use PHPUnit\Framework\TestCase;

class BackButtonTest extends TestCase
{
    /**
     * @var BackButton
     */
    private $backButton;

    /**
     * @var GenericButton|\PHPUnit\Framework\MockObject\MockObject
     */
    private $genericButtonMock;

    /**
     * @var UrlInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $urlBuilderMock;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->genericButtonMock = $this->getMockBuilder(GenericButton::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlBuilderMock = $this->getMockBuilder(UrlInterface::class)
            ->getMockForAbstractClass();

        $this->backButton = $objectManager->getObject(
            BackButton::class,
            [
                'data' => ['genericButton' => $this->genericButtonMock],
                'urlBuilder' => $this->urlBuilderMock,
            ]
        );
    }

    /**
     * @covers \Auraine\Schedule\Block\Adminhtml\Schedule\Edit\BackButton::getButtonData
     */
    public function testGetButtonData(): void
    {
        $backUrl = 'http://localhost/admin/schedule/index';

        $this->genericButtonMock->expects($this->once())
            ->method('getUrl')
            ->with('*/*/')
            ->willReturn($backUrl);

        $buttonData = $this->backButton->getButtonData();

        $this->assertArrayHasKey('label', $buttonData);
        $this->assertEquals('Back', $buttonData['label']);

        $this->assertArrayHasKey('on_click', $buttonData);
        $this->assertEquals(sprintf("location.href = '%s';", $backUrl), $buttonData['on_click']);

        $this->assertArrayHasKey('class', $buttonData);
        $this->assertEquals('back', $buttonData['class']);

        $this->assertArrayHasKey('sort_order', $buttonData);
        $this->assertEquals(10, $buttonData['sort_order']);
    }

    /**
     * @covers \Auraine\Schedule\Block\Adminhtml\Schedule\Edit\BackButton::getBackUrl
     */
    public function testGetBackUrl(): void
    {
        $backUrl = 'http://localhost/admin/schedule/index';

        $this->genericButtonMock->expects($this->once())
            ->method('getUrl')
            ->with('*/*/')
            ->willReturn($backUrl);

        $this->assertEquals($backUrl, $this->backButton->getBackUrl());
    }
}