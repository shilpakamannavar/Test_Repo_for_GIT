<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\PopularSearch\Setup\Patch\Data;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class AddPopularSearchAttribute implements DataPatchInterface, PatchRevertableInterface
{
    const POPULAR_SEARCH = 'popular_search';
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        if (!$eavSetup->getAttributeId(\Magento\Catalog\Model\Category::ENTITY, self::POPULAR_SEARCH)) {
            $eavSetup->removeAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                self::POPULAR_SEARCH
            );
     }
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Category::ENTITY, self::POPULAR_SEARCH);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            self::POPULAR_SEARCH,
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

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->removeAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            self::POPULAR_SEARCH
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [

        ];
    }
}

