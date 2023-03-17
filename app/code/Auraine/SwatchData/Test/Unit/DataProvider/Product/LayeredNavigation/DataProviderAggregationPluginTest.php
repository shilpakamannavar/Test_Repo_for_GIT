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

    public function testBuild()
    {
        // Test case for build() method
        $aggregation = $this->getMockBuilder(AggregationInterface::class)->getMock();
        $storeId = 1;

        $result = $this->dataProviderAggregationPlugin->build($aggregation, $storeId);

        // Asserts the output value of the method
        $this->assertIsArray($result);
    }

    public function testGetSwatchType()
    {
        // Test case for getSwatchType() method
        $valueType = 1;

        $result = $this->dataProviderAggregationPlugin->getSwatchType($valueType);

        // Asserts the output value of the method
        $this->assertIsString($result);
        $this->assertEquals('ColorSwatchData', $result);
    }
}
