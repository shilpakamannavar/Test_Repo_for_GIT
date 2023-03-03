<?php

namespace Auraine\Staticcontent\Test\Unit\Block\Adminhtml\Content\Edit;

use Auraine\Staticcontent\Block\Adminhtml\Content\Edit\DeleteButton;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\TestFramework\Unit\BaseTestCase;

class DeleteButtonTest extends BaseTestCase
{
    /**
     * @var DeleteButton
     */
    protected $deleteButton;

    protected function setUp(): void
    {
        $context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->deleteButton = new DeleteButton($context);
    }

    /**
     * Test getButtonData method with model ID
     */
    public function testGetButtonDataWithModelId()
    {
        $this->deleteButton->setModelId(1);
        $buttonData = $this->deleteButton->getButtonData();

        $this->assertArrayHasKey('label', $buttonData);
        $this->assertArrayHasKey('class', $buttonData);
        $this->assertArrayHasKey('on_click', $buttonData);
        $this->assertArrayHasKey('sort_order', $buttonData);

        $this->assertEquals(__('Delete Content'), $buttonData['label']);
        $this->assertEquals('delete', $buttonData['class']);
        $this->assertStringContainsString('deleteConfirm', $buttonData['on_click']);
        $this->assertStringContainsString($this->deleteButton->getDeleteUrl(), $buttonData['on_click']);
        $this->assertEquals(20, $buttonData['sort_order']);
    }

    /**
     * Test getButtonData method without model ID
     */
    public function testGetButtonDataWithoutModelId()
    {
        $buttonData = $this->deleteButton->getButtonData();

        $this->assertEmpty($buttonData);
    }

    /**
     * Test getDeleteUrl method with model ID
     */
    public function testGetDeleteUrlWithModelId()
    {
        $this->deleteButton->setModelId(1);
        $expectedUrl = '*/*/delete/content_id/1';
        $actualUrl = $this->deleteButton->getDeleteUrl();

        $this->assertEquals($expectedUrl, $actualUrl);
    }

    /**
     * Test getDeleteUrl method without model ID
     */
    public function testGetDeleteUrlWithoutModelId()
    {
        $expectedUrl = '*/*/delete/content_id/';
        $actualUrl = $this->deleteButton->getDeleteUrl();

        $this->assertEquals($expectedUrl, $actualUrl);
    }
}
