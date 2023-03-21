<?php

use Auraine\SwatchData\Model\OrderItem\OptionsProcessoroverride;
use Magento\Sales\Api\Data\OrderItemInterface;
use PHPUnit\Framework\TestCase;

class OptionsProcessoroverrideTest extends TestCase
{
    /**
     * File Path argument constant
     */
    const TEST_CUSTOME = 'Test customization';
    
    /**
     * @covers \Auraine\SwatchData\Model\OrderItem\OptionsProcessoroverride::getItemOptions
     */
    public function testGetItemOptionsWithNoOptions()
    {
        $orderItem = $this->getMockBuilder(OrderItemInterface::class)
                        ->addMethods(['getProductOptions'])
                        ->getMockForAbstractClass();
        $orderItem->method('getProductOptions')->willReturn(null);
        
        $optionsProcessor = new OptionsProcessoroverride();
        $options = $optionsProcessor->getItemOptions($orderItem);
        
        $this->assertIsArray($options);
        $this->assertEmpty($options['selected_options']);
        $this->assertEmpty($options['entered_options']);
    }
    
    /**
     * @covers \Auraine\SwatchData\Model\OrderItem\OptionsProcessoroverride::getItemOptions
     */
    public function testGetItemOptionsWithOptions()
{
    $options = [
        'options' => [
            [
                'option_type' => 'drop_down',
                'label' => 'Color',
                'value' => 'Blue',
                'print_value' => 'Blue',
                'option_value' => 'blue',
            ],
            [
                'option_type' => 'field',
                'label' => 'Customization',
                'value' => self::TEST_CUSTOME,
            ],
        ],
        'attributes_info' => [
                    'option_type' => 'drop_down',
                    'label' => 'Color',
                    'value' => 'Blue',
                    'print_value' => 'Blue',
                    'option_value' => 'blue',
                    'attributes_info' => true,
                    'value_label' => 'dadf'
                ],
    ];
    
    $orderItem = $this->getMockBuilder(OrderItemInterface::class)
                      ->addMethods(['getProductOptions'])
                      ->getMockForAbstractClass();

    $orderItem->method('getProductOptions')->willReturn($options);
    
    $optionsProcessor = new OptionsProcessoroverride();
    $options = $optionsProcessor->getItemOptions($orderItem);
    
    $this->assertIsArray($options);
    $this->assertCount(1, $options['selected_options']);
    $this->assertCount(1, $options['entered_options']);
    
    $selectedOption = $options['selected_options'][0];
    $this->assertEquals('Customization', $selectedOption['label']);
    $this->assertEquals(self::TEST_CUSTOME, $selectedOption['value']);
    $this->assertEquals(self::TEST_CUSTOME, $selectedOption['value_label']);
    
    $enteredOption = $options['entered_options'][0];
    $this->assertEquals('Color', $enteredOption['label']);
    $this->assertEquals('Blue', $enteredOption['value']);
    $this->assertArrayNotHasKey('value_label1', $enteredOption);

}


}
