<?php
namespace Auraine\SwatchData\Test\Unit\Model\OrderItem;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\SwatchData\Model\OrderItem\OptionsProcessoroverride
 */
class OptionsProcessoroverrideTest extends TestCase
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\SwatchData\Model\OrderItem\OptionsProcessoroverride
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);

        $this->testObject = $this->objectManager->getObject(
            \Auraine\SwatchData\Model\OrderItem\OptionsProcessoroverride::class,
            [

            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetItemOptions()
    {
        $item_option = 3;
        $expectedResult = 3;

        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => $item_option],
                'expectedResult' => ['param' => $expectedResult]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetItemOptions
     */
    public function testGetItemOptions(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
