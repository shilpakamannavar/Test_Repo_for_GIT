<?php

namespace Auraine\Staticcontent\Test\Unit\Model\Resolver;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\Staticcontent\Model\Resolver\ContentList
 */
class ContentListTest extends TestCase
{
    /**
     * Mock valueInstance
     *
     * @var \Auraine\Staticcontent\Model\ResourceModel\Content\Collection|PHPUnit\Framework\MockObject\MockObject
     */
    private $valueInstance;

    /**
     * Mock value
     *
     * @var \Auraine\Staticcontent\Model\ResourceModel\Content\CollectionFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $value;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\Staticcontent\Model\Resolver\ContentList
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->valueInstance = $this->createMock(\Auraine\Staticcontent\Model\ResourceModel\Content\Collection::class);
        $this->value = $this->createMock(\Auraine\Staticcontent\Model\ResourceModel\Content\CollectionFactory::class);
        $this->value->method('create')->willReturn($this->valueInstance);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\Staticcontent\Model\Resolver\ContentList::class,
            [
                'value' => $this->value,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestResolve()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestResolve
     */
    public function testResolve(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
