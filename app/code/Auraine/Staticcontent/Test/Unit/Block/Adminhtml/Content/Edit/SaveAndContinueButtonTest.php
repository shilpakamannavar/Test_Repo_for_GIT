<?php
// @codingStandardsIgnoreFile
namespace Auraine\Staticcontent\Test\Unit\Block\Adminhtml\Content\Edit;

use Auraine\Staticcontent\Block\Adminhtml\Content\Edit\SaveAndContinueButton;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\TestFramework\Unit\BaseTestCase;

class SaveAndContinueButtonTest extends BaseTestCase
{
    /**
     * @var SaveAndContinueButton
     */
    protected $saveAndContinueButton;

    protected function setUp(): void
    {
        $context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->saveAndContinueButton = new SaveAndContinueButton($context);
    }

    /**
     * Test getButtonData method
     */
    public function testGetButtonData()
    {
        $buttonData = $this->saveAndContinueButton->getButtonData();

        $this->assertArrayHasKey('label', $buttonData);
        $this->assertArrayHasKey('class', $buttonData);
        $this->assertArrayHasKey('data_attribute', $buttonData);
        $this->assertArrayHasKey('sort_order', $buttonData);

        $this->assertEquals(__('Save and Continue Edit'), $buttonData['label']);
        $this->assertEquals('save', $buttonData['class']);
        $this->assertArrayHasKey('mage-init', $buttonData['data_attribute']);
        $this->assertArrayHasKey('button', $buttonData['data_attribute']['mage-init']);
        $this->assertArrayHasKey('event', $buttonData['data_attribute']['mage-init']['button']);
        $this->assertEquals('saveAndContinueEdit', $buttonData['data_attribute']['mage-init']['button']['event']);
        $this->assertEquals(80, $buttonData['sort_order']);
    }
}
