<?php
/**
 * Catalog entity setup
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Auraine\PopularSearch\Setup;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ResourceModel\Category;
use Magento\Catalog\Model\ResourceModel\Category\Attribute\Collection;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Eav\Model\Entity\Setup\Context;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

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
    public const CATEGORY_ENTITY_TYPE_ID = 3;

    /**
     * This should be set explicitly
     */
    public const CATALOG_PRODUCT_ENTITY_TYPE_ID = 4;

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
