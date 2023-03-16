<?php

declare(strict_types=1);

namespace Auraine\ExtendCatalogGraphQl\Plugin\Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Product\CollectionProcessor;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Auraine\ExtendCatalogGraphQl\Plugin\Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Product\CollectionProcessor\StockProcessor as Stock;
use PHPUnit\Framework\TestCase;

class StockProcessorTest extends TestCase
{
    public function testAroundProcessWithStockFlag()
    {
        $collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $collection->expects($this->once())
            ->method('hasFlag')
            ->with('has_stock_status_filter')
            ->willReturn(true);

        $proceed = function ($collection, $searchCriteria, $attributeNames) {
            return $collection;
        };

        $storeProcess = $this->getMockBuilder(\Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Product\CollectionProcessor\StockProcessor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $subject = new Stock;
        
        $result = $subject->aroundProcess(
            $storeProcess,
            $proceed,
            $collection,
            $this->createMock(\Magento\Framework\Api\SearchCriteriaInterface::class),
            []
        );

        
        
        // ($collection, $proceed, $collection, [], []);

        $this->assertSame($collection, $result);
    }

    public function testAroundProcessWithoutStockFlag()
    {
        $collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $collection->expects($this->once())
            ->method('hasFlag')
            ->with('has_stock_status_filter')
            ->willReturn(false);

        $collection->expects($this->once())
            ->method('setFlag')
            ->with('has_stock_status_filter', true);

        $proceed = function ($collection, $searchCriteria, $attributeNames) {
            return $collection;
        };

        $storeProcess = $this->getMockBuilder(\Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Product\CollectionProcessor\StockProcessor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $subject = new Stock;
        
        $result = $subject->aroundProcess(
            $storeProcess,
            $proceed,
            $collection,
            $this->createMock(\Magento\Framework\Api\SearchCriteriaInterface::class),
            []
        );


        $this->assertSame($collection, $result);
    }

    public function testAroundProcessWithSearchCriteria()
    {
        $collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $collection->expects($this->once())
            ->method('hasFlag')
            ->with('has_stock_status_filter')
            ->willReturn(false);

        $collection->expects($this->once())
            ->method('setFlag')
            ->with('has_stock_status_filter', true);

        $proceed = function ($collection, $searchCriteria, $attributeNames) {
            return $collection;
        };

        $searchCriteria = $this->getMockBuilder(SearchCriteriaInterface::class)
            ->getMock();

      

        $storeProcess = $this->getMockBuilder(\Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Product\CollectionProcessor\StockProcessor::class)
        ->disableOriginalConstructor()
        ->getMock();

    $subject = new Stock;
    
 

        $result = $subject->aroundProcess($storeProcess, $proceed, $collection, $searchCriteria, []);

        $this->assertSame($collection, $result);
    }

  
}
