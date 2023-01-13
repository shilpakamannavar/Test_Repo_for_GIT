<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\DiscountPercentageFilter\Test\Unit\Setup;

use Auraine\DiscountPercentageFilter\Setup\DiscountPercentageFilterSetup;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class DiscountPercentageFilterSetupTest extends TestCase
{
    /** @var DiscountPercentageFilterSetup */
    protected $unit;

    protected function setUp(): void
    {
        $this->unit = (new ObjectManager($this))->getObject(
            DiscountPercentageFilterSetup::class
        );
    }

    public function testGetDefaultEntitiesContainAllAttributes()
    {
        $defaultEntities = $this->unit->getDefaultEntities();

        $this->assertEquals(
            [
                'name',
                'sku',
                'description',
                'short_description',
                'price',
                'special_price',
                'special_from_date',
                'special_to_date',
                'cost',
                'weight',
                'manufacturer',
                'meta_title',
                'meta_keyword',
                'meta_description',
                'image',
                'small_image',
                'thumbnail',
                'media_gallery',
                'old_id',
                'tier_price',
                'color',
                'news_from_date',
                'news_to_date',
                'gallery',
                'status',
                'minimal_price',
                'visibility',
                'custom_design',
                'custom_design_from',
                'custom_design_to',
                'custom_layout_update',
                'page_layout',
                'category_ids',
                'options_container',
                'required_options',
                'has_options',
                'image_label',
                'small_image_label',
                'thumbnail_label',
                'created_at',
                'updated_at',
                'country_of_manufacture',
                'quantity_and_stock_status',
                'discount',
            ],
            array_keys($defaultEntities['catalog_product']['attributes'])
        );
    }
}
