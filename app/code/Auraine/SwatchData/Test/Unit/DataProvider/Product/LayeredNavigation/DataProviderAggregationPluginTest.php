<?php

namespace Auraine\SwatchData\Test\Unit\DataProvider\Product\LayeredNavigation;

use Auraine\SwatchData\DataProvider\Product\LayeredNavigation\DataProviderAggregationPlugin;
use Magento\Eav\Model\Config;
use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Swatches\Block\LayeredNavigation\RenderLayered;
use Magento\Swatches\Helper\Data;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Auraine\SwatchData\DataProvider\Product\LayeredNavigation\DataProviderAggregationPlugin
 */
class DataProviderAggregationPluginTest extends TestCase
{
    private $objectManager;
    private $builders;
    private $logger;
    private $eavConfig;
    private $swatchHelper;
    private $renderLayered;
    private $scopeConfig;
    private $dataProviderAggregationPlugin;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        $this->builders = [];
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $this->eavConfig = $this->getMockBuilder(Config::class)->disableOriginalConstructor()->getMock();
        $this->swatchHelper = $this->getMockBuilder(Data::class)->disableOriginalConstructor()->getMock();
        $this->renderLayered = $this->getMockBuilder(RenderLayered::class)->disableOriginalConstructor()->getMock();
        $this->scopeConfig = $this->getMockBuilder(
            \Magento\Framework\App\Config\ScopeConfigInterface::class
        )->getMock();

        $this->dataProviderAggregationPlugin = $this->objectManager->getObject(
            DataProviderAggregationPlugin::class,
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
     * @covers \Auraine\SwatchData\DataProvider\Product\LayeredNavigation\DataProviderAggregationPlugin::build
     */
    public function testBuild()
    {
        // Test case for build() method
        $aggregation = $this->getMockBuilder(AggregationInterface::class)->getMock();
        $storeId = 1;

        $result = $this->dataProviderAggregationPlugin->build($aggregation, $storeId);

        // Asserts the output value of the method
        $this->assertIsArray($result);
    }

    /**
     * @covers \Auraine\SwatchData\DataProvider\Product\LayeredNavigation\DataProviderAggregationPlugin::getSwatchType
     */
    public function testGetSwatchType()
    {
        // Test case for getSwatchType() method with value type = 1
        $valueType = 1;

        $result = $this->dataProviderAggregationPlugin->getSwatchType($valueType);

        // Asserts the output value of the method
        $this->assertIsString($result);
        $this->assertEquals('ColorSwatchData', $result);

        // Test case for getSwatchType() method with value type = 0
        $valueType = 0;

        $result = $this->dataProviderAggregationPlugin->getSwatchType($valueType);

        // Asserts the output value of the method
        $this->assertIsString($result);
        $this->assertEquals('TextSwatchData', $result);

        // Test case for getSwatchType() method with value type = 2
        $valueType = 2;

        $result = $this->dataProviderAggregationPlugin->getSwatchType($valueType);

        // Asserts the output value of the method
        $this->assertIsString($result);
        $this->assertEquals('ImageSwatchData', $result);

        // Test case for getSwatchType() method with value type = 3
        $valueType = 3;

        $result = $this->dataProviderAggregationPlugin->getSwatchType($valueType);

        // Asserts the output value of the method
        $this->assertEquals(null, $result);
    }
}
