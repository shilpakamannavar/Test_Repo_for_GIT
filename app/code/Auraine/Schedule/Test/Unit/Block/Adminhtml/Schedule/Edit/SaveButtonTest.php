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

class SaveButtonTest extends TestCase
{
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
        $this->urlBuilder
            ->expects($this->once())
            ->method('getUrl')
            ->with('', [])
            ->willReturn('http://example.com/schedule/save');
        $expectedData = [
            'label' => __('Save Schedule'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
        $this->assertSame($expectedData, $this->saveButton->getButtonData());
    }

    public function testGetButtonDataWithCustomUrl(): void
    {
        $this->urlBuilder
            ->expects($this->once())
            ->method('getUrl')
            ->with('custom/route', ['param' => 'value'])
            ->willReturn('http://example.com/custom/route?param=value');
        $this->saveButton->setRoute('custom/route');
        $this->saveButton->setParams(['param' => 'value']);
        $expectedData = [
            'label' => __('Save Schedule'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
        $this->assertSame($expectedData, $this->saveButton->getButtonData());
    }
}
