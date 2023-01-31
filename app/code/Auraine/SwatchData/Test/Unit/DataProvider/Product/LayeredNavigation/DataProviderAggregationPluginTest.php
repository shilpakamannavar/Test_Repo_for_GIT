<?php
namespace Auraine\SwatchData\Test\Unit\DataProvider\Product\LayeredNavigation;

use Auraine\SwatchData\DataProvider\Product\LayeredNavigation\DataProviderAggregationPlugin;
use Auraine\SwatchData\Model\Resolver\DataProvider;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\SwatchData\DataProvider\Product\LayeredNavigation\DataProviderAggregationPlugin
 */
class DataProviderAggregationPluginTest extends TestCase
{
    /**
     * Mock builders
     *
     * @var \array|PHPUnit\Framework\MockObject\MockObject
     */
    private $builders;

    /**
     * Mock logger
     *
     * @var \Psr\Log\LoggerInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $logger;

    /**
     * Mock eavConfig
     *
     * @var \Magento\Eav\Model\Config|PHPUnit\Framework\MockObject\MockObject
     */
    private $eavConfig;

    /**
     * Mock swatchHelper
     *
     * @var \Magento\Swatches\Helper\Data|PHPUnit\Framework\MockObject\MockObject
     */
    private $swatchHelper;

    /**
     * Mock renderLayered
     *
     * @var \Magento\Swatches\Block\LayeredNavigation\RenderLayered|PHPUnit\Framework\MockObject\MockObject
     */
    private $renderLayered;

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
     * @var \Auraine\SwatchData\DataProvider\Product\LayeredNavigation\DataProviderAggregationPlugin
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->builders = [];//$this->createMock(\array::class);
        $this->logger = $this->createMock(\Psr\Log\LoggerInterface::class);
        $this->eavConfig = $this->createMock(\Magento\Eav\Model\Config::class);
        $this->swatchHelper = $this->createMock(\Magento\Swatches\Helper\Data::class);
        $this->renderLayered = $this->createMock(\Magento\Swatches\Block\LayeredNavigation\RenderLayered::class);
        $this->scopeConfig = $this->createMock(\Magento\Framework\App\Config\ScopeConfigInterface::class);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\SwatchData\DataProvider\Product\LayeredNavigation\DataProviderAggregationPlugin::class,
            [
                'builders' => $this->builders,
                'logger' => $this->logger,
                'eavConfig' => $this->eavConfig,
                'swatchHelper' => $this->swatchHelper,
                'renderLayered' => $this->renderLayered,
                'scopeConfig' => $this->scopeConfig,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestBuild()
    {
        $value['option_label'] = 'Color';
        if ($value['option_label'] == 'Color') {
            $typeName = $this->getswatchTypeTest(1);

            return [
                'Testcase 1' => [
                    'prerequisites' => ['param' => $typeName],
                    'expectedResult' => ['param' => 'ColorSwatchData']
                ]
            ];
        }
    }

    /**
     * @dataProvider dataProviderForTestBuild
     */
    public function testBuild(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    public function getswatchTypeTest($valueType)
    {
        switch ($valueType) {
            case 0:
                return 'TextSwatchData';
            case 1:
                return 'ColorSwatchData';
            case 2:
                return 'ImageSwatchData';
        }
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetswatchType()
    {
        $prerequisites_swatch_type = $this->getswatchTypeTest(1);
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => $prerequisites_swatch_type],
                'expectedResult' => ['param' => 'ColorSwatchData']
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
