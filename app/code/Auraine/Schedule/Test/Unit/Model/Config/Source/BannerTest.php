<?php

namespace Auraine\Schedule\Test\Unit\Model\Config\Source;

use Auraine\Schedule\Model\Config\Source\Banner;
use Auraine\BannerSlider\Api\BannerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use PHPUnit\Framework\TestCase;

class BannerTest extends TestCase
{
    /**
     * @var BannerRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $bannerRepositoryMock;
    
    /**
     * @var SearchCriteriaBuilderFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $searchCriteriaBuilderFactoryMock;
    
    /**
     * @var SearchCriteriaBuilder|\PHPUnit\Framework\MockObject\MockObject
     */
    private $searchCriteriaBuilderMock;
    
    /**
     * @var Banner
     */
    private $model;
    
    protected function setUp(): void
    {
        $this->bannerRepositoryMock = $this->createMock(BannerRepositoryInterface::class);
        $this->searchCriteriaBuilderFactoryMock = $this->createMock(SearchCriteriaBuilderFactory::class);
        $this->searchCriteriaBuilderMock = $this->createMock(SearchCriteriaBuilder::class);
        
        $this->model = new Banner(
            $this->bannerRepositoryMock,
            $this->searchCriteriaBuilderFactoryMock
        );
    }
    
    public function testToOptionArray()
    {
        $items = [
            (object) ['entity_id' => 1, 'title' => 'Banner 1'],
            (object) ['entity_id' => 2, 'title' => 'Banner 2'],
        ];
        
        $this->searchCriteriaBuilderFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->searchCriteriaBuilderMock);
        
        $this->bannerRepositoryMock
            ->expects($this->once())
            ->method('getList')
            ->with($this->searchCriteriaBuilderMock->create())
            ->willReturn((object) ['items' => $items]);
        
        $options = $this->model->toOptionArray();
        
        $this->assertCount(3, $options);
        $this->assertContains(['value' => '', 'label' => 'Select Banner'], $options);
        $this->assertContains(['value' => 1, 'label' => 'Banner 1'], $options);
        $this->assertContains(['value' => 2, 'label' => 'Banner 2'], $options);
    }
}
