<?php
// @codingStandardsIgnoreFile
namespace Auraine\Staticcontent\Test\Unit\Block\Adminhtml\Type\Edit;

use Auraine\Staticcontent\Block\Adminhtml\Type\Edit\SaveButton;
use Auraine\Staticcontent\Block\Adminhtml\Type\Edit\GenericButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class SaveButtonTest extends TestCase
{
    /**
     * @var SaveButton
     */
    protected $saveButton;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $genericButtonMock = $this->getMockBuilder(GenericButton::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->saveButton = $objectManager->getObject(
            SaveButton::class,
            [
                'data' => [],
                'genericButton' => $genericButtonMock,
            ]
        );
    }

    public function testInstanceOfButtonProviderInterface()
    {
        $this->assertInstanceOf(ButtonProviderInterface::class, $this->saveButton);
    }

    public function testGetButtonData()
    {
        $expectedData = [
            'label' => __('Save Type'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];

        $this->assertEquals($expectedData, $this->saveButton->getButtonData());
    }
}
