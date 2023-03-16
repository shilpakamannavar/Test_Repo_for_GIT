<?php
namespace Auraine\PopularSearch\Test\Unit\Setup;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Model\Category;
use PHPUnit\Framework\TestCase;

class InstallDataTest extends TestCase
{
    /**
     * @var EavSetupFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $eavSetupFactoryMock;

    /**
     * @var InstallData
     */
    private $installData;

    /**
     * @var ModuleDataSetupInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $moduleDataSetupMock;

    /**
     * @var ModuleContextInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $moduleContextMock;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->eavSetupFactoryMock = $this->createMock(EavSetupFactory::class);
        $this->moduleDataSetupMock = $this->createMock(ModuleDataSetupInterface::class);
        $this->moduleContextMock = $this->createMock(ModuleContextInterface::class);
        $this->installData = new \Auraine\PopularSearch\Setup\InstallData($this->eavSetupFactoryMock);
    }

    /**
     * Test install method
     *
     * @return void
     */
    public function testInstall(): void
    {
        $eavSetupMock = $this->createMock(\Magento\Eav\Setup\EavSetup::class);

        $this->eavSetupFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($eavSetupMock);

        $eavSetupMock->expects($this->once())
            ->method('addAttribute')
            ->with(
                Category::ENTITY,
                'popular_search',
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
                    'option' => '',
                ]
            );

        $this->installData->install($this->moduleDataSetupMock, $this->moduleContextMock);
    }
}
