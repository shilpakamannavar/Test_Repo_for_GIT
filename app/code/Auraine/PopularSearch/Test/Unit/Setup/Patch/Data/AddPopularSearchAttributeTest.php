<?php

namespace Auraine\PopularSearch\Test\Unit\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Catalog\Model\Category;
use PHPUnit\Framework\TestCase;
use Auraine\PopularSearch\Setup\Patch\Data\AddPopularSearchAttribute;

class AddPopularSearchAttributeTest extends TestCase
{
    /**
     * @var AddPopularSearchAttribute
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

        $this->patch = new AddPopularSearchAttribute($this->moduleDataSetupMock, $this->eavSetupFactoryMock);
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
            ->with(Category::ENTITY, AddPopularSearchAttribute::POPULAR_SEARCH)
            ->willReturn($this->attributeMock);

        $this->moduleDataSetupMock->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->getMockBuilder(\Magento\Framework\DB\Adapter\AdapterInterface::class)
                ->getMock());

        $this->assertNull($this->patch->revert());
    }

    /**
     * testApplyAddsPopularSearchAttributeToCategoryEntity
     *
     * @return void
     */
    public function testApplyAddsPopularSearchAttributeToCategoryEntity()
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
            ->with(Category::ENTITY, AddPopularSearchAttribute::POPULAR_SEARCH)
            ->willReturn(false);
        $eavSetup->expects($this->any())
            ->method('removeAttribute')
            ->with(Category::ENTITY, AddPopularSearchAttribute::POPULAR_SEARCH);
        $eavSetup->expects($this->once())
            ->method('addAttribute')
            ->with(
                Category::ENTITY,
                AddPopularSearchAttribute::POPULAR_SEARCH,
                [
                    'group' => 'General Information',
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Enable Popular Search',
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

        $object = new AddPopularSearchAttribute($moduleDataSetup, $eavSetupFactory);
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
            ->with(Category::ENTITY, AddPopularSearchAttribute::POPULAR_SEARCH)
            ->willReturn(false);
        $eavSetup->expects($this->any())
            ->method('removeAttribute')
            ->with(Category::ENTITY, AddPopularSearchAttribute::POPULAR_SEARCH);
        $eavSetup->expects($this->once())
            ->method('addAttribute')
            ->with(
                Category::ENTITY,
                AddPopularSearchAttribute::POPULAR_SEARCH,
                [
                    'group' => 'General Information',
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Enable Popular Search',
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

        $patch = new AddPopularSearchAttribute($moduleDataSetup, $eavSetupFactory);

        $patch->apply();
    }

    public function testGetAliases()
    {
        $patch = new AddPopularSearchAttribute($this->moduleDataSetupMock, $this->eavSetupFactoryMock);

        $this->assertEquals([], $patch->getAliases());
    }

    public function testGetDependencies()
    {
        $patch = new AddPopularSearchAttribute($this->moduleDataSetupMock, $this->eavSetupFactoryMock);

        $this->assertEquals([], $patch->getDependencies());
    }
}
