<?php
namespace Auraine\Category\Test\Unit\Setup\Patch;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Catalog\Model\Category;
use PHPUnit\Framework\TestCase;
use Auraine\Category\Setup\Patch\Data\AddCategoryImage2CategoryAttribute;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class AddCategoryImage2CategoryAttributeTest extends TestCase
{

    /**
     * @var AddCategoryImage2CategoryAttribute
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

        $this->patch = new AddCategoryImage2CategoryAttribute($this->moduleDataSetupMock, $this->eavSetupFactoryMock);
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
            ->with(Category::ENTITY, AddCategoryImage2CategoryAttribute::CATEGORY_IMAGE2_SLIDER)
            ->willReturn($this->attributeMock);

        $this->moduleDataSetupMock->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->getMockBuilder(\Magento\Framework\DB\Adapter\AdapterInterface::class)
                ->getMock());

        $this->assertNull($this->patch->revert());
    }

    /**
     * testApplyAddBannerSliderAttributeToCategoryEntity
     *
     * @return void
     */
    public function testApplyAddsCategoryImage2AttributeToCategoryEntity()
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
        $eavSetup->expects($this->any())
            ->method('removeAttribute')
            ->with(Category::ENTITY, AddCategoryImage2CategoryAttribute::CATEGORY_IMAGE2_SLIDER);
        $eavSetup->expects($this->once())
            ->method('addAttribute')
            ->with(
                Category::ENTITY,
                AddCategoryImage2CategoryAttribute::CATEGORY_IMAGE2_SLIDER,
                [
                'type' => 'varchar',
                'label' => 'Category image 2',
                'input' => 'image',
                'sort_order' => 333,
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => null,
                'group' => 'General Information',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image'
                ]
            );

        $moduleDataSetup->expects($this->exactly(2))
            ->method('getConnection')
            ->willReturnSelf();
        $moduleDataSetup->expects($this->exactly(1))
            ->method('startSetup');
        $moduleDataSetup->expects($this->exactly(1))
            ->method('endSetup');

        $object = new AddCategoryImage2CategoryAttribute($moduleDataSetup, $eavSetupFactory);
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
        $eavSetup->expects($this->any())
            ->method('removeAttribute')
            ->with(Category::ENTITY, AddCategoryImage2CategoryAttribute::CATEGORY_IMAGE2_SLIDER);
        $eavSetup->expects($this->once())
            ->method('addAttribute')
            ->with(
                Category::ENTITY,
                AddCategoryImage2CategoryAttribute::CATEGORY_IMAGE2_SLIDER,
                [
                'type' => 'varchar',
                'label' => 'Category image 2',
                'input' => 'image',
                'sort_order' => 333,
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => null,
                'group' => 'General Information',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image'
                ]
            );

        $moduleDataSetup->expects($this->exactly(2))
            ->method('getConnection')
            ->willReturnSelf();
        $moduleDataSetup->expects($this->exactly(1))
            ->method('startSetup');
        $moduleDataSetup->expects($this->exactly(1))
            ->method('endSetup');

        $patch = new AddCategoryImage2CategoryAttribute($moduleDataSetup, $eavSetupFactory);

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

        $patch = new AddCategoryImage2CategoryAttribute($moduleDataSetup, $eavSetupFactory);

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

        $patch = new AddCategoryImage2CategoryAttribute($moduleDataSetup, $eavSetupFactory);

        $this->assertEquals([], $patch->getAliases());
    }
}
