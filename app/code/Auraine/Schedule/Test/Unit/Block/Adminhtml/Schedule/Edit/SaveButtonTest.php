<?php
declare(strict_types=1);

namespace Auraine\Schedule\Test\Unit\Block\Adminhtml\Schedule\Edit;

use Auraine\Schedule\Block\Adminhtml\Schedule\Edit\GenericButton;
use Auraine\Schedule\Block\Adminhtml\Schedule\Edit\SaveButton;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Phrase;

class SaveButtonTest extends TestCase
{
    public const URL = 'http://example.com/schedule/save';

    /**
     * @var MockObject|Context
     */
    private $context;

    /**
     * @var MockObject|UrlInterface
     */
    private $urlBuilder;

    /**
     * @var SaveButton
     */
    private $saveButton;

    protected function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $this->urlBuilder = $this->createMock(UrlInterface::class);
        $this->context
            ->method('getUrlBuilder')
            ->willReturn($this->urlBuilder);
        $this->saveButton = new SaveButton($this->context);
    }

    public function testInstanceOfButtonProviderInterface(): void
    {
        $this->assertInstanceOf(ButtonProviderInterface::class, $this->saveButton);
    }

    public function testGetButtonData(): void
    {

        $buttonData = $this->saveButton->getButtonData();
        $this->assertArrayHasKey('label', $buttonData);
        $this->assertArrayHasKey('class', $buttonData);
        $this->assertArrayHasKey('data_attribute', $buttonData);
        $this->assertArrayHasKey('sort_order', $buttonData);
        $result = $buttonData['label'];
        $this->assertInstanceOf(Phrase::class, $result);


        $this->assertSame('Save Schedule', $result->getText());
        $this->assertSame('save primary', $buttonData['class']);
        $this->assertArrayHasKey('mage-init', $buttonData['data_attribute']);
        $this->assertArrayHasKey('button', $buttonData['data_attribute']['mage-init']);
        $this->assertArrayHasKey('form-role', $buttonData['data_attribute']);
        $this->assertArrayHasKey('event', $buttonData['data_attribute']['mage-init']['button']);
        $this->assertSame('save', $buttonData['data_attribute']['mage-init']['button']['event']);
        $this->assertSame('save', $buttonData['data_attribute']['form-role']);
     
        $this->assertSame(90, $buttonData['sort_order']);
    }
}
