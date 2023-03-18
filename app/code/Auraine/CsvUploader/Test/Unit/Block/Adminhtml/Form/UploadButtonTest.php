<?php

namespace Auraine\CsvUploader\Test\Unit\Block\Adminhtml\Form;

use Auraine\CsvUploader\Block\Adminhtml\Form\UploadButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use PHPUnit\Framework\TestCase;

class UploadButtonTest extends TestCase
{
    /**
     * @var UploadButton
     */
    protected $uploadButton;

    protected function setUp(): void
    {
        $this->uploadButton = new UploadButton();
    }

    public function testGetButtonData()
    {
        $expectedButtonData = [
            'label' => __('Upload'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
        $this->assertEquals($expectedButtonData, $this->uploadButton->getButtonData());
    }
}
