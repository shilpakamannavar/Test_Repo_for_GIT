<?php

namespace Auraine\DiscountPercentageFilter\Test\Unit\Helper;

use Auraine\DiscountPercentageFilter\Helper\Data;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Request\Http;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    /** @var Data */
    private $dataHelper;

    /** @var Http|MockObject */
    private $httpMock;

    /** @var Resolver|MockObject */
    private $resolverMock;

    protected function setUp(): void
    {
        $contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->httpMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resolverMock = $this->getMockBuilder(Resolver::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataHelper = new Data(
            $contextMock,
            $this->httpMock,
            $this->resolverMock
        );
    }

    public function testIsCurrentPageCategoryPageReturnsTrueWhenFullActionNameIsCatalogCategoryView()
    {
        $this->httpMock->method('getFullActionName')
            ->willReturn('catalog_category_view');

        $this->assertTrue($this->dataHelper->isCurrentPageCategoryPage());
    }

    public function testIsCurrentPageCategoryPageReturnsFalseWhenFullActionNameIsNotCatalogCategoryView()
    {
        $this->httpMock->method('getFullActionName')
            ->willReturn('other_action');

        $this->assertFalse($this->dataHelper->isCurrentPageCategoryPage());
    }






    public function testGetDiscountVarReturnsCorrectValue()
    {
        $this->assertEquals('10% and above', $this->dataHelper->getDiscountVar(10));
        $this->assertEquals('10% and above', $this->dataHelper->getDiscountVar(15));
        $this->assertEquals('20% and above', $this->dataHelper->getDiscountVar(20));
        $this->assertEquals('20% and above', $this->dataHelper->getDiscountVar(25));
        $this->assertEquals('30% and above', $this->dataHelper->getDiscountVar(30));
        $this->assertEquals('30% and above', $this->dataHelper->getDiscountVar(35));
        $this->assertEquals('40% and above', $this->dataHelper->getDiscountVar(40));
        $this->assertEquals('40% and above', $this->dataHelper->getDiscountVar(45));
        $this->assertEquals('50% and above', $this->dataHelper->getDiscountVar(50));
        $this->assertEquals('50% and above', $this->dataHelper->getDiscountVar(55));
        $this->assertEquals('60% and above', $this->dataHelper->getDiscountVar(60));
        $this->assertEquals('60% and above', $this->dataHelper->getDiscountVar(65));
        $this->assertEquals('70% and above', $this->dataHelper->getDiscountVar(70));
        $this->assertEquals('70% and above', $this->dataHelper->getDiscountVar(75));
        $this->assertEquals('80% and above', $this->dataHelper->getDiscountVar(80));
        $this->assertEquals('80% and above', $this->dataHelper->getDiscountVar(85));
        $this->assertEquals('90% and above', $this->dataHelper->getDiscountVar(90));
        $this->assertEquals('90% and above', $this->dataHelper->getDiscountVar(95));
        $this->assertEquals(null, $this->dataHelper->getDiscountVar(102));
    }
}
