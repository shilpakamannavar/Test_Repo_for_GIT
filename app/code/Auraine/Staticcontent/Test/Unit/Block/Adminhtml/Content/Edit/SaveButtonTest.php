<?php

namespace Auraine\Staticcontent\Test\Unit\Block\Adminhtml\Content\Edit;

use Auraine\Staticcontent\Block\Adminhtml\Content\Edit\SaveButton;
use Magento\Framework\TestFramework\Unit\BaseTestCase;
use Magento\Backend\Block\Widget\Context;

class SaveButtonTest extends BaseTestCase
{
    /**
     * @var SaveButton
     */
    protected $saveButton;

    protected function setUp(): void
    {
        $context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->saveButton = new SaveButton($context);
    }

    /**
     * Test getButtonData method
     */
    public function testGetButtonData()
    {
        $buttonData = $this->saveButton->getButtonData();

        $this->assertArrayHasKey('label', $buttonData);
        $this->assertArrayHasKey('class', $buttonData);
        $this->assertArrayHasKey('data_attribute', $buttonData);
        $this->assertArrayHasKey('sort_order', $buttonData);
        $this->assertIsArray($buttonData['data_attribute']);
        $this->assertEquals('save', $buttonData['data_attribute']['form-role']);
        $this->assertEquals('Save Content', $buttonData['label']);
        $this->assertEquals('save primary', $buttonData['class']);
        $this->assertEquals(90, $buttonData['sort_order']);
        $this->assertArrayHasKey('mage-init', $buttonData['data_attribute']);
        $this->assertIsArray($buttonData['data_attribute']['mage-init']);
        $this->assertArrayHasKey('button', $buttonData['data_attribute']['mage-init']);
        $this->assertIsArray($buttonData['data_attribute']['mage-init']['button']);
        $this->assertEquals('save', $buttonData['data_attribute']['mage-init']['button']['event']);
    }
}
