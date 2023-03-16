<?php
namespace Auraine\PopularSearch\Test\Unit\Setup;

use Auraine\PopularSearch\Setup\CategorySetup;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use PHPUnit\Framework\TestCase;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory;

class CategorySetupTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ModuleDataSetupInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $moduleDataSetupMock;

    /**
     * @var CategoryFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $categoryFactoryMock;

    /**
     * @var CacheInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $cacheMock;

    /**
     * @var CategorySetup
     */
    private $categorySetup;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->moduleDataSetupMock = $this->createMock(ModuleDataSetupInterface::class);
        $this->categoryFactoryMock = $this->createMock(CategoryFactory::class);
        $this->cacheMock = $this->createMock(CacheInterface::class);

        $context = $this->objectManager->getObject(\Magento\Eav\Model\Entity\Setup\Context::class, [
            'moduleDataSetup' => $this->moduleDataSetupMock,
        ]);

        $collectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->categorySetup = $this->objectManager->getObject(CategorySetup::class, [
            'setup' => $this->moduleDataSetupMock,
            'context' => $context,
            'cache' => $this->cacheMock,
            'attrGroupCollectionFactory' => $collectionFactoryMock,
            'categoryFactory' => $this->categoryFactoryMock,
        ]);
    }

    public function testCreateCategory()
    {
        $categoryData = [
            'name' => 'Test Category',
            'is_active' => true,
            'popular_search' => true,
        ];

        $categoryMock = $this->createMock(\Magento\Catalog\Model\Category::class);

        $this->categoryFactoryMock->expects($this->once())
            ->method('create')
            ->with($categoryData)
            ->willReturn($categoryMock);

        $result = $this->categorySetup->createCategory($categoryData);

        $this->assertSame($categoryMock, $result);
    }

    public function testGetDefaultEntities()
    {
        $expected = [
            'catalog_category' => [
                'entity_type_id' => CategorySetup::CATEGORY_ENTITY_TYPE_ID,
                'entity_model' => \Magento\Catalog\Model\ResourceModel\Category::class,
                'attribute_model' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::class,
                'table' => 'catalog_category_entity',
                'additional_attribute_table' => 'catalog_eav_attribute',
                'entity_attribute_collection' =>
                    \Magento\Catalog\Model\ResourceModel\Category\Attribute\Collection::class,
                'attributes' => [
                    'popular_search' => [
                        'type' => 'int',
                        'label' => 'Is Popular Search',
                        'input' => 'select',
                        'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                        'required' => false,
                        'sort_order' => 30,
                        'group' => 'Display Settings',
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $this->categorySetup->getDefaultEntities());
    }
}
