<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Auraine\PopularSearch\Setup;
    
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;

class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

/**
 * {@inheritdoc}
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;

        $installer->startSetup();


        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
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
            'option' => ''
            ]
        );
    }
}
