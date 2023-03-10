<?php
/**
 * Catalog entity setup
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Auraine\PopularSearch\Setup;

use Magento\Catalog\Block\Adminhtml\Category\Helper\Pricestep;
use Magento\Catalog\Block\Adminhtml\Category\Helper\Sortby\Available;
use Magento\Catalog\Block\Adminhtml\Category\Helper\Sortby\DefaultSortby;
use Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Category as CategoryFormHelper;
use Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Weight as WeightFormHelper;
use Magento\Catalog\Model\Attribute\Backend\Customlayoutupdate;
use Magento\Catalog\Model\Attribute\Backend\Startdate;
use Magento\Catalog\Model\Category\Attribute\Backend\Image;
use Magento\Catalog\Model\Category\Attribute\Backend\Sortby as SortbyBackendModel;
use Magento\Catalog\Model\Category\Attribute\Source\Layout;
use Magento\Catalog\Model\Category\Attribute\Source\Mode;
use Magento\Catalog\Model\Category\Attribute\Source\Page;
use Magento\Catalog\Model\Category\Attribute\Source\Sortby;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Entity\Product\Attribute\Design\Options\Container;
use Magento\Catalog\Model\Product\Attribute\Backend\Category as CategoryBackendAttribute;
use Magento\Catalog\Model\Product\Attribute\Backend\Price;
use Magento\Catalog\Model\Product\Attribute\Backend\Sku;
use Magento\Catalog\Model\Product\Attribute\Backend\Stock;
use Magento\Catalog\Model\Product\Attribute\Backend\Tierprice;
use Magento\Catalog\Model\Product\Attribute\Backend\Weight;
use Magento\Catalog\Model\Product\Attribute\Frontend\Image as ImageFrontendModel;
use Magento\Catalog\Model\Product\Attribute\Source\Countryofmanufacture;
use Magento\Catalog\Model\Product\Attribute\Source\Layout as LayoutModel;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Category;
use Magento\Catalog\Model\ResourceModel\Category\Attribute\Collection;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\CatalogInventory\Block\Adminhtml\Form\Field\Stock as StockField;
use Magento\CatalogInventory\Model\Source\Stock as StockSourceModel;
use Magento\CatalogInventory\Model\Stock as StockModel;
use Magento\Eav\Model\Entity\Attribute\Backend\Datetime;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Eav\Model\Entity\Setup\Context;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\Theme\Model\Theme\Source\Theme;

/**
 * Setup category with default entities.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CategorySetup extends EavSetup
{
    /**
     * Category model factory
     *
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * This should be set explicitly
     */
    const CATEGORY_ENTITY_TYPE_ID = 3;

    /**
     * This should be set explicitly
     */
    const CATALOG_PRODUCT_ENTITY_TYPE_ID = 4;

    /**
     * Init
     *
     * @param ModuleDataSetupInterface $setup
     * @param Context $context
     * @param CacheInterface $cache
     * @param CollectionFactory $attrGroupCollectionFactory
     * @param CategoryFactory $categoryFactory
     */
    public function __construct(
        ModuleDataSetupInterface $setup,
        Context $context,
        CacheInterface $cache,
        CollectionFactory $attrGroupCollectionFactory,
        CategoryFactory $categoryFactory
    ) {
        $this->categoryFactory = $categoryFactory;
        parent::__construct($setup, $context, $cache, $attrGroupCollectionFactory);
    }

    /**
     * Creates category model
     *
     * @param array $data
     * @return \Magento\Catalog\Model\Category
     * @codeCoverageIgnore
     */
    public function createCategory($data = [])
    {
        return $this->categoryFactory->create($data);
    }

    /**
     * Default entities and attributes
     *
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getDefaultEntities()
    {
        return [
            'catalog_category' => [
                'entity_type_id' => self::CATEGORY_ENTITY_TYPE_ID,
                'entity_model' => Category::class,
                'attribute_model' => Attribute::class,
                'table' => 'catalog_category_entity',
                'additional_attribute_table' => 'catalog_eav_attribute',
                'entity_attribute_collection' =>
                    Collection::class,
                'attributes' => [
                    'popular_search' => [
                        'type' => 'int',
                        'label' => 'Is Popular Search',
                        'input' => 'select',
                        'source' => Boolean::class,
                        'required' => false,
                        'sort_order' => 30,
                        'group' => 'Display Settings',
                    ],
                ],
            ],
        ];
    }
}
