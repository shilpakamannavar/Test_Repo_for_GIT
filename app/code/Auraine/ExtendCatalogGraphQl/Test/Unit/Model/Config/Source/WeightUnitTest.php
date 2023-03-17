<?php
declare(strict_types=1);

namespace Auraine\ExtendCatalogGraphQl\Test\Unit\Model\Config\Source;

use Auraine\ExtendCatalogGraphQl\Model\Config\Source\WeightUnit;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class WeightUnitTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var WeightUnit
     */
    private $weightUnit;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        $this->weightUnit = $this->objectManager->getObject(WeightUnit::class);
    }

    public function testToOptionArray()
    {
        $expectedResult = [
            ['value' => 'lbs', 'label' => __('lbs')],
            ['value' => 'kgs', 'label' => __('kgs')],
            ['value' => 'gms', 'label' => __('grams')]
        ];
        $result = $this->weightUnit->toOptionArray();
        $this->assertEquals($expectedResult, $result);
    }
}
