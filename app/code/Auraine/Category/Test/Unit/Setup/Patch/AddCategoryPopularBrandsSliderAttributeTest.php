<?php
namespace Auraine\Category\Test\Unit\Setup\Patch;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Catalog\Model\Category;
use PHPUnit\Framework\TestCase;
use Auraine\Category\Setup\Patch\Data\AddCategoryPopularBrandsSliderAttribute;

class AddCategoryPopularBrandsSliderAttributeTest extends TestCase
{
    /**
     * @var AddCategoryPopularBrandsSliderAttribute
     */
    private $patch;

    /**
     * @var ModuleDataSetupInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $moduleDataSetupMock;

    /**
     * @var EavSetupFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $eavSetupFactoryMock;

    /**
     * @var Attribute|\PHPUnit\Framework\MockObject\MockObject
     */
    private $attributeMock;

    /**
     * Set up method.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->moduleDataSetupMock = $this->getMockBuilder(ModuleDataSetupInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eavSetupFactoryMock = $this->getMockBuilder(EavSetupFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->attributeMock = $this->getMockBuilder(Attribute::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->patch = new AddCategoryPopularBrandsSliderAttribute($this->moduleDataSetupMock, $this->eavSetupFactoryMock);
    }

    /**
     * testRevertRemovesAttribute()
     *
     * @return void
     */
    public function testRevertRemovesAttribute()
    {
        $eavSetupMock = $this->getMockBuilder(\Magento\Eav\Setup\EavSetup::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eavSetupFactoryMock->expects($this->once())
            ->method('create')
            ->with(['setup' => $this->moduleDataSetupMock])
            ->willReturn($eavSetupMock);

        $eavSetupMock->expects($this->once())
            ->method('removeAttribute')
            ->with(Category::ENTITY, AddCategoryPopularBrandsSliderAttribute::CATEGORY_POPULAR_BRANDS_SLIDER)
            ->willReturn($this->attributeMock);

        $this->moduleDataSetupMock->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->getMockBuilder(\Magento\Framework\DB\Adapter\AdapterInterface::class)
                ->getMock());

        $this->assertNull($this->patch->revert());
    }

    /**
     * testApplyAddPopularBrandsSliderAttributeToCategoryEntity
     *
     * @return void
     */
    public function testApplyAddsAddPopularBrandsSliderAttributeToCategoryEntity()
    {
        $moduleDataSetup = $this->getMockBuilder(ModuleDataSetupInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eavSetup = $this->getMockBuilder(EavSetup::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eavSetupFactory = $this->getMockBuilder(EavSetupFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eavSetupFactory->expects($this->once())
            ->method('create')
            ->with(['setup' => $moduleDataSetup])
            ->willReturn($eavSetup);
        $eavSetup->expects($this->once())
            ->method('getAttributeId')
            ->with(Category::ENTITY, AddCategoryPopularBrandsSliderAttribute::CATEGORY_POPULAR_BRANDS_SLIDER)
            ->willReturn(false);
        $eavSetup->expects($this->any())
            ->method('removeAttribute')
            ->with(Category::ENTITY, AddCategoryPopularBrandsSliderAttribute::CATEGORY_POPULAR_BRANDS_SLIDER);
        $eavSetup->expects($this->once())
            ->method('addAttribute')
            ->with(
                Category::ENTITY,
                AddCategoryPopularBrandsSliderAttribute::CATEGORY_POPULAR_BRANDS_SLIDER,
                [
                'group' => 'General Information',
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Popular Brands Slider',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => true,
                'filterable' => true,
                'comparable' => false,
                'is_used_in_grid' => true,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'option' => ''
                ]
            );

        $moduleDataSetup->expects($this->exactly(2))
            ->method('getConnection')
            ->willReturnSelf();
        $moduleDataSetup->expects($this->exactly(1))
            ->method('startSetup');
        $moduleDataSetup->expects($this->exactly(1))
            ->method('endSetup');

        $object = new AddCategoryPopularBrandsSliderAttribute($moduleDataSetup, $eavSetupFactory);
        $object->apply();
    }

    /**
     * testApply
     *
     * @return void
     */
    public function testApply()
    {
        $moduleDataSetup = $this->getMockBuilder(ModuleDataSetupInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eavSetup = $this->getMockBuilder(EavSetup::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eavSetupFactory = $this->getMockBuilder(EavSetupFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eavSetupFactory->expects($this->once())
            ->method('create')
            ->with(['setup' => $moduleDataSetup])
            ->willReturn($eavSetup);
        $eavSetup->expects($this->once())
            ->method('getAttributeId')
            ->with(Category::ENTITY, AddCategoryPopularBrandsSliderAttribute::CATEGORY_POPULAR_BRANDS_SLIDER)
            ->willReturn(false);
        $eavSetup->expects($this->any())
            ->method('removeAttribute')
            ->with(Category::ENTITY, AddCategoryPopularBrandsSliderAttribute::CATEGORY_POPULAR_BRANDS_SLIDER);
        $eavSetup->expects($this->once())
            ->method('addAttribute')
            ->with(
                Category::ENTITY,
                AddCategoryPopularBrandsSliderAttribute::CATEGORY_POPULAR_BRANDS_SLIDER,
                [
                'group' => 'General Information',
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Popular Brands Slider',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => true,
                'filterable' => true,
                'comparable' => false,
                'is_used_in_grid' => true,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'option' => ''
                ]
            );

        $moduleDataSetup->expects($this->exactly(2))
            ->method('getConnection')
            ->willReturnSelf();
        $moduleDataSetup->expects($this->exactly(1))
            ->method('startSetup');
        $moduleDataSetup->expects($this->exactly(1))
            ->method('endSetup');

        $patch = new AddCategoryPopularBrandsSliderAttribute($moduleDataSetup, $eavSetupFactory);

        $patch->apply();
    }

    public function testGetAliases()
    {
        $moduleDataSetup = $this->getMockBuilder(ModuleDataSetupInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $eavSetupFactory = $this->getMockBuilder(EavSetupFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $patch = new AddCategoryPopularBrandsSliderAttribute($moduleDataSetup, $eavSetupFactory);

        $this->assertEquals([], $patch->getAliases());
    }

    public function testGetDependencies()
    {
        $moduleDataSetup = $this->getMockBuilder(ModuleDataSetupInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $eavSetupFactory = $this->getMockBuilder(EavSetupFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $patch = new AddCategoryPopularBrandsSliderAttribute($moduleDataSetup, $eavSetupFactory);

        $this->assertEquals([], $patch->getAliases());
    }
}
