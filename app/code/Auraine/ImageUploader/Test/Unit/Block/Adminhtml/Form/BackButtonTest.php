<?php

namespace Auraine\ImageUploader\Test\Unit\Block\Adminhtml\Form;

use Auraine\ImageUploader\Block\Adminhtml\Form\BackButton;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use PHPUnit\Framework\TestCase;

class BackButtonTest extends TestCase
{
    public const URL = 'http://example.com';

    /**
     * Test getButtonData method of BackButton class
     */
    public function testGetButtonData()
    {
        $urlInterfaceMock = $this->getMockBuilder(UrlInterface::class)
            ->getMock();
        $urlInterfaceMock->expects($this->once())
            ->method('getUrl')
            ->with('*/*/')
            ->willReturn(self::URL);

        $backButton = new BackButton($urlInterfaceMock);

        $buttonData = $backButton->getButtonData();

        $this->assertIsArray($buttonData);
        $this->assertArrayHasKey('label', $buttonData);
        $this->assertArrayHasKey('on_click', $buttonData);
        $this->assertArrayHasKey('class', $buttonData);
        $this->assertArrayHasKey('sort_order', $buttonData);
        $this->assertEquals('Back', $buttonData['label']);
        $this->assertEquals("location.href = ".self::URL.";", $buttonData['on_click']);
        $this->assertEquals('back', $buttonData['class']);
        $this->assertEquals(10, $buttonData['sort_order']);
    }

    /**
     * Test getBackUrl method of BackButton class
     */
    public function testGetBackUrl()
    {
        $urlInterfaceMock = $this->getMockBuilder(UrlInterface::class)
            ->getMock();
        $urlInterfaceMock->expects($this->once())
            ->method('getUrl')
            ->with('*/*/')
            ->willReturn(self::URL);

        $backButton = new BackButton($urlInterfaceMock);

        $backUrl = $backButton->getBackUrl();

        $this->assertEquals('http://example.com', $backUrl);
    }
}
