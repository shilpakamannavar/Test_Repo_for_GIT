<?php

namespace Auraine\ImageUploader\Test\Unit\Block\Adminhtml\Form;

use Auraine\ImageUploader\Block\Adminhtml\Form\UploadButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use PHPUnit\Framework\TestCase;

class UploadButtonTest extends TestCase
{
    /**
     * Test getButtonData method of UploadButton class
     */
    public function testGetButtonData()
    {
        $uploadButton = new UploadButton();

        $buttonData = $uploadButton->getButtonData();

        $this->assertIsArray($buttonData);
        $this->assertArrayHasKey('label', $buttonData);
        $this->assertArrayHasKey('class', $buttonData);
        $this->assertArrayHasKey('data_attribute', $buttonData);
        $this->assertArrayHasKey('mage-init', $buttonData['data_attribute']);
        $this->assertArrayHasKey('form-role', $buttonData['data_attribute']);
        $this->assertEquals('Upload', $buttonData['label']);
        $this->assertEquals('save primary', $buttonData['class']);
        $this->assertEquals('save', $buttonData['data_attribute']['form-role']);
        $this->assertEquals(90, $buttonData['sort_order']);
    }
}

