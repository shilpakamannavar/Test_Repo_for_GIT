<?php
declare(strict_types=1);

namespace Auraine\SwatchData\Test\Unit\Model\Resolver;

use Auraine\SwatchData\Model\Resolver\DataProvider;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Swatches\Helper\Data;
use Magento\Store\Model\ScopeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class DataProviderTest extends TestCase
{
    /** @var DataProvider */
    private $dataProvider;

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
        $this->swatchHelperMock = $this->createMock(Data::class);
        $this->scopeConfigMock = $this->createMock(ScopeConfigInterface::class);
        $this->objectManager = new ObjectManager($this);
        $this->dataProvider = $this->objectManager->getObject(
            DataProvider::class,
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

        
        if ($baseUrl) {
            $this->scopeConfigMock->expects($this->once())
                ->method('getValue')
                ->with(
                    'swatch_data/general/swatch_data_base_url',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
                ->willReturn($baseUrl);
        }
        $this->dataProvider->resolve($field, $context, $resolveInfo, $value, $args);

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
     * @dataProvider getSwatchTypeDataProvider
     * @covers \Auraine\SwatchData\Model\Resolver\DataProvider::getSwatchType
     *
     * @param int $valueType
     * @param string $expectedResult
     */
    public function testGetSwatchType(int $valueType, string $expectedResult): void
    {
        $result = $this->dataProvider->getSwatchType($valueType);
        $this->assertEquals($expectedResult, $result);

        // Test case for getSwatchType() method with value type = 3
        $valueType = 3;

        $result = $this->dataProvider->getSwatchType($valueType);

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




    /**
     * This will return type of swatch by id
     *
     * @param int $valueType
     * @return string
     */
    public function getSwatchType(int $valueType): string
    {
        $types = [
            0 => 'TextSwatchData',
            1 => 'ColorSwatchData',
            2 => 'ImageSwatchData',
            3 => null,
        ];

        return $types[$valueType] ?? 'UnknownSwatchType';
    }

}
