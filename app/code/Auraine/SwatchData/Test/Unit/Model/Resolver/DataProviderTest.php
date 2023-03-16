<?php

use Auraine\SwatchData\Model\Resolver\DataProvider;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Swatches\Helper\Data;
use PHPUnit\Framework\TestCase;

class DataProviderTest extends TestCase
{
    private $swatchHelperMock;
    private $scopeConfigMock;
    private $dataProvider;

    public function setUp(): void
    {
        $this->swatchHelperMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->getMock();

        $this->dataProvider = new DataProvider(
            $this->swatchHelperMock,
            $this->scopeConfigMock
        );
    }

    public function testResolveReturnsNullWhenOptionLabelIsNotColor()
    {
        $field = $this->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();
        $context = $this->getMockBuilder(ContextInterface::class)
            ->getMock();
        $info = $this->getMockBuilder(ResolveInfo::class)
            ->disableOriginalConstructor()
            ->getMock();
        $value = [
            'option_label' => 'Size',
            'value_id' => 1
        ];
        $args = null;

        $result = $this->dataProvider->resolve($field, $context, $info, $value, $args);

        $this->assertNull($result);
    }

    public function testResolveReturnsExpectedResultWhenOptionLabelIsColor()
    {
        $field = $this->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();
        $context = $this->getMockBuilder(ContextInterface::class)
            ->getMock();
        $info = $this->getMockBuilder(ResolveInfo::class)
            ->disableOriginalConstructor()
            ->getMock();
        $value = [
            'option_label' => 'Color',
            'value_id' => 1
        ];
        $args = null;

        $swatchData = [
            'type' => 1,
            'value' => 'FFFFFF'
        ];

        $this->swatchHelperMock->expects($this->once())
            ->method('getSwatchesByOptionsId')
            ->with([$value['value_id']])
            ->willReturn([$value['value_id'] => $swatchData]);

        $expectedResult = [
            'type' => 'ColorSwatchData',
            'value' => 'FFFFFF'
        ];

        $result = $this->dataProvider->resolve($field, $context, $info, $value, $args);

        $this->assertEquals($expectedResult, $result);
    }

    public function testGetSwatchTypeReturnsExpectedResult()
    {
        $valueType = 1;

        $result = $this->dataProvider->getSwatchType($valueType);

        $this->assertEquals('ColorSwatchData', $result);
    }
}
