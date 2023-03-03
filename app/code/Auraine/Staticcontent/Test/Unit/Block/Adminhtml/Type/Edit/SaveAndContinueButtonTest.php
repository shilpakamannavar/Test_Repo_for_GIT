<?php
namespace Auraine\Staticcontent\Test\Unit\Block\Adminhtml\Type\Edit;

use Auraine\Staticcontent\Block\Adminhtml\Type\Edit\SaveAndContinueButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use PHPUnit\Framework\TestCase;

class SaveAndContinueButtonTest extends TestCase
{
    /**
     * @var SaveAndContinueButton
     */
    private $saveAndContinueButton;

    protected function setUp(): void
    {
        $this->saveAndContinueButton = new SaveAndContinueButton(
            $this->createMock(\Magento\Backend\Block\Widget\Context::class),
            $this->createMock(\Magento\Framework\Registry::class),
            $this->createMock(\Magento\Framework\App\RequestInterface::class)
        );
    }

    public function testGetButtonData()
    {
        $buttonData = $this->saveAndContinueButton->getButtonData();
        $this->assertArrayHasKey('label', $buttonData);
        $this->assertArrayHasKey('class', $buttonData);
        $this->assertArrayHasKey('data_attribute', $buttonData);
        $this->assertArrayHasKey('mage-init', $buttonData['data_attribute']);
        $this->assertArrayHasKey('button', $buttonData['data_attribute']['mage-init']);
        $this->assertEquals('saveAndContinueEdit', $buttonData['data_attribute']['mage-init']['button']['event']);
        $this->assertEquals(80, $buttonData['sort_order']);
    }
}
