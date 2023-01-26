<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Category\Test\Unit\Setup;

use Auraine\Category\Setup\CategorySetup;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class CategorySetupTest extends TestCase
{
    /** @var CategorySetup */
    protected $unit;

    protected function setUp(): void
    {
        $this->unit = (new ObjectManager($this))->getObject(
            CategorySetup::class
        );
    }

    public function testGetDefaultEntitiesContainAllAttributes()
    {
        $defaultEntities = $this->unit->getDefaultEntities();
        $this->assertEquals(
            [
                'name',
                'is_active',
                'description',
                'image',
                'meta_title',
                'meta_keywords',
                'meta_description',
                'display_mode',
                'landing_page',
                'is_anchor',
                'path',
                'position',
                'all_children',
                'path_in_store',
                'children',
                'custom_design',
                'custom_design_from',
                'custom_design_to',
                'page_layout',
                'custom_layout_update',
                'level',
                'children_count',
                'available_sort_by',
                'default_sort_by',
                'include_in_menu',
                'custom_use_parent_settings',
                'custom_apply_to_products',
                'filter_price_range',
                'category_banner_slider_id',
                'category_blogs_slider_id',
                'category_offers_slider_id',
                'category_our_exclusives_top_slider_id',
                'category_our_exclusives_slider_id',
                'category_popular_brands_slider_id'

            ],
            array_keys($defaultEntities['catalog_category']['attributes'])
        );
    }
}
