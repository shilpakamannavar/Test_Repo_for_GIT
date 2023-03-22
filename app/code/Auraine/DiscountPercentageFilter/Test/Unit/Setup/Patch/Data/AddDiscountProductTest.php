<?php

namespace Auraine\DiscountPercentageFilter\Test\Unit\Setup\Patch\Data;

use PHPUnit\Framework\TestCase;
use Auraine\DiscountPercentageFilter\Setup\Patch\Data\AddDiscountProduct;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

/**
 * Summary of AddDiscountProductTest
 */
class AddDiscountProductTest extends TestCase
{
    /**
     * @var AddDiscountProduct
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
        $this->patch = new AddDiscountProduct(
            $this->moduleDataSetupMock,
            $this->eavSetupFactoryMock
        );
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
            ->with(Product::ENTITY, 'discount')
            ->willReturn($this->attributeMock);
        $this->moduleDataSetupMock->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->getMockBuilder(\Magento\Framework\DB\Adapter\AdapterInterface::class)
                ->getMock());
        $this->assertNull($this->patch->revert());
    }
    /**
     * testApply
     *
     * @return void
     */
    public function testApply()
    {
        $eavSetup = $this->getMockBuilder(EavSetup::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->eavSetupFactoryMock->expects($this->once())
            ->method('create')
            ->with(['setup' => $this->moduleDataSetupMock])
            ->willReturn($eavSetup);
        $eavSetup->expects($this->once())
            ->method('addAttribute')
            ->with(
                Product::ENTITY,
                'discount',
                [
                    'type' => 'int',
                    'label' => 'Discount',
                    'input' => 'select',
                    'source' => '',
                    'frontend' => '',
                    'required' => false,
                    'backend' => '',
                    'sort_order' => '30',
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'default' => null,
                    'visible' => true,
                    'user_defined' => true,
                    'searchable' => false,
                    'filterable' => true,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'unique' => false,
                    'apply_to' => '',
                    'group' => 'General',
                    'used_in_product_listing' => false,
                    'is_used_in_grid' => true,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => false,
                    'option' => ['values' => [
                        "10% And Above",
                        "20% And Above",
                        "30% And Above",
                        "40% And Above",
                        "50% And Above",
                        "60% And Above",
                        "70% And Above",
                        "80% And Above"
                    ]
                    ]
                ]
            );
        $this->moduleDataSetupMock->expects($this->exactly(2))
            ->method('getConnection')
            ->willReturnSelf();
        $this->moduleDataSetupMock->expects($this->exactly(1))
            ->method('startSetup');
        $this->moduleDataSetupMock->expects($this->exactly(1))
            ->method('endSetup');
        $patch = new AddDiscountProduct($this->moduleDataSetupMock, $this->eavSetupFactoryMock);
        $patch->apply();
    }
    /**
     * Summary of testGetAliases
     * @return void
     */
    public function testGetAliases()
    {
        $patch = new AddDiscountProduct($this->moduleDataSetupMock, $this->eavSetupFactoryMock);
        $this->assertEquals([], $patch->getAliases());
    }
    /**
     * Summary of testGetDependencies
     * @return void
     */
    public function testGetDependencies()
    {
        $patch = new AddDiscountProduct($this->moduleDataSetupMock, $this->eavSetupFactoryMock);
        $this->assertEquals([], $patch->getDependencies());
    }
}
