<?php
declare(strict_types=1);

namespace Auraine\Schedule\Test\Unit\Block\Adminhtml\Schedule\Edit;

use Auraine\Schedule\Block\Adminhtml\Schedule\Edit\SaveAndContinueButton;
use Auraine\Schedule\Block\Adminhtml\Schedule\Edit\GenericButton;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use PHPUnit\Framework\TestCase;

class SaveAndContinueButtonTest extends TestCase
{
    /** @var SaveAndContinueButton */
    private $button;

    protected function setUp(): void
    {
        $context = $this->createMock(Context::class);
        $this->button = new SaveAndContinueButton($context);
    }

    public function testInstanceOfButtonProviderInterface(): void
    {
        $this->assertInstanceOf(ButtonProviderInterface::class, $this->button);
    }

    public function testGetButtonData(): void
    {
        $buttonData = $this->button->getButtonData();
        $this->assertArrayHasKey('label', $buttonData);
        $this->assertArrayHasKey('class', $buttonData);
        $this->assertArrayHasKey('data_attribute', $buttonData);
        $this->assertArrayHasKey('sort_order', $buttonData);

        $this->assertSame('Save and Continue Edit', $buttonData['label']);
        $this->assertSame('save', $buttonData['class']);
        $this->assertArrayHasKey('mage-init', $buttonData['data_attribute']);
        $this->assertArrayHasKey('button', $buttonData['data_attribute']['mage-init']);
        $this->assertArrayHasKey('event', $buttonData['data_attribute']['mage-init']['button']);
        $this->assertSame('saveAndContinueEdit', $buttonData['data_attribute']['mage-init']['button']['event']);
        $this->assertSame(80, $buttonData['sort_order']);
    }
}
