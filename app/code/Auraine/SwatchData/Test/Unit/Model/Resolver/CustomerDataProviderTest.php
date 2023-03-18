<?php
declare(strict_types=1);

namespace Auraine\SwatchData\Test\Unit\Model\Resolver;

use Auraine\SwatchData\Model\Resolver\CustomerDataProvider;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Swatches\Helper\Data;
use Magento\Framework\App\Config\ScopeConfigInterface ;
use PHPUnit\Framework\TestCase;

class CustomerDataProviderTest extends TestCase
{
    /**
     * @var CustomerDataProvider
     */
    private $customerDataProvider;

    /**
     * @var Data|\PHPUnit\Framework\MockObject\MockObject
     */
    private $swatchHelperMock;

    /**
     * @var ScopeConfigInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $scopeConfigMock;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->swatchHelperMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerDataProvider = $this->objectManager->getObject(
            CustomerDataProvider::class,
            [
                'swatchHelper' => $this->swatchHelperMock,
                'scopeConfig' => $this->scopeConfigMock,
            ]
        );
    }

    /**
     * @dataProvider resolveDataProvider
     *
     * @param array $value
     * @param string $typeName
     * @param string $hexCode
     * @param string|null $baseUrl
     * @param array $expectedResult
     */
    public function testResolve(
        array $value,
        string $typeName,
        string $hexCode,
        ?string $baseUrl,
        array $expectedResult
    ): void
    {
        $field = $this->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();

        $context = [];

        $resolveInfo = $this->getMockBuilder(ResolveInfo::class)
            ->disableOriginalConstructor()
            ->getMock();

        $args = [];

        $this->swatchHelperMock->expects($this->once())
            ->method('getSwatchesByOptionsId')
            ->with([$value['value']])
            ->willReturn([$value['value'] => ['type' => 1, 'value' => $hexCode]]);

        if ($baseUrl) {
            $this->scopeConfigMock->expects($this->once())
                ->method('getValue')
                ->with(
                    'swatch_data/general/swatch_data_base_url',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
                ->willReturn($baseUrl);
        }

        $result = $this->customerDataProvider->resolve($field, $context, $resolveInfo, $value, $args);

        $this->assertEquals($expectedResult, $result);
    }

    public function resolveDataProvider(): array
    {
        return [
            'color text swatch' => [
                ['label' => 'Color', 'value' => 123],
                'ColorSwatchData',
                'FFFFFF',
                null,
                ['type' => 'ColorSwatchData', 'value' => 'FFFFFF'],
            ]
        ];
    }


    /**
     * Test for CustomerDataProvider::resolve method with incorrect input data.
     *
     * @dataProvider incorrectInputDataProvider
     * @param array $inputData
     */
    public function testResolveMethodWithIncorrectInputData(array $inputData)
    {
        $objectManager = new ObjectManager($this);
        $resolver = $objectManager->getObject(CustomerDataProvider::class, [
            'swatchHelper' => $this->swatchHelperMock,
            'scopeConfig' => $this->scopeConfigMock,
        ]);

        $expectedResult = null;

        if (isset($inputData['attribute_code'])) {
            $result = $resolver->resolve($inputData);
            $this->assertEquals($expectedResult, $result);
        }

    }


    /**
     * Data provider for testResolveMethodWithIncorrectInputData test case.
     *
     * @return array
     */
    public function incorrectInputDataProvider(): array
    {
        return [
            [[]],
            [['label' => 'Size']],
            [['label' => 'Color']],
            [['label' => 'Color', 'value' => null]],
            [['label' => 'Color', 'value' => '']],
        ];
    }

    /**
     * Test for CustomerDataProvider::getswatchType method.
     *
     * @dataProvider getSwatchTypeDataProvider
     * @param int $valueType
     * @param string $expectedResult
     */
    public function testGetSwatchTypeMethod(int $valueType, string $expectedResult)
    {
        $resolver = $this->objectManager->getObject(CustomerDataProvider::class, [
            'swatchHelper' => $this->swatchHelperMock,
            'scopeConfig' => $this->scopeConfigMock,
        ]);

        $result = $resolver->getswatchType($valueType);
        $this->assertSame($expectedResult, $result);

         // Test case for getSwatchType() method with value type = 3
         $valueType = 3;

         $result = $resolver->getSwatchType($valueType);

         // Asserts the output value of the method
         $this->assertEquals(null, $result);
    }

    /**
     * Data provider for testGetSwatchTypeMethod test case.
     *
     * @return array
     */
    public function getSwatchTypeDataProvider(): array
    {
        return [
            [0, 'TextSwatchData'],
            [1, 'ColorSwatchData'],
            [2, 'ImageSwatchData']
        ];
    }
}
