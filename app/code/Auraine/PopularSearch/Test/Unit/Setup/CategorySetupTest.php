<?php
namespace Auraine\PopularSearch\Test\Unit\Setup;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\PopularSearch\Setup\CategorySetup
 */
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
               'popular_search'
            ],
            array_keys($defaultEntities['catalog_category']['attributes'])
        );
    }
}
