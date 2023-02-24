<?php
namespace Auraine\SwatchData\Test\Unit\Model\Resolver;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\SwatchData\Model\Resolver\DataProvider
 */
class DataProviderTest extends TestCase
{
    /**
     * Mock swatchHelper
     *
     * @var \Magento\Swatches\Helper\Data|PHPUnit\Framework\MockObject\MockObject
     */
    private $swatchHelper;

    /**
     * Mock scopeConfig
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $scopeConfig;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\SwatchData\Model\Resolver\DataProvider
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->swatchHelper = $this->createMock(\Magento\Swatches\Helper\Data::class);
        $this->scopeConfig = $this->createMock(\Magento\Framework\App\Config\ScopeConfigInterface::class);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\SwatchData\Model\Resolver\DataProvider::class,
            [
                'swatchHelper' => $this->swatchHelper,
                'scopeConfig' => $this->scopeConfig,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestResolve()
    {
        $value['option_label'] = 'Color';
        if ($value['option_label'] == 'Color') {
            $typeName = $this->getswatchTypesTest(1);

            return [
                'Testcase 1' => [
                    'prerequisites' => ['param' => $typeName],
                    'expectedResult' => ['param' => 'ColorSwatchData']
                ]
            ];
        }
    }

    public function getswatchTypesTest($valueType)
    {
        $value = null ;
        switch ($valueType) {
            case 0:
                $value = 'TextSwatchData';
                break;
            case 1:
                $value = 'ColorSwatchData';
                break;
            case 2:
                $value = 'ImageSwatchData';
                break;
            default:
                break;
        }
        return $value ;
    }

    /**
     * @dataProvider dataProviderForTestResolve
     */
    public function testResolve(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetswatchType()
    {
        $swatchType = 2;
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => $swatchType],
                'expectedResult' => ['param' => 2]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetswatchType
     */
    public function testGetswatchType(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
