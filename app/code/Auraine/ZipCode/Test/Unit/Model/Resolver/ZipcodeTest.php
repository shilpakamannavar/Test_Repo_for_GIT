<?php
namespace Auraine\ZipCode\Test\Unit\Model\Resolver;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\ZipCode\Model\Resolver\Zipcode
 */
class ZipcodeTest extends TestCase
{
    /**
     * Mock zipcodeDataProvider
     *
     * @var \Auraine\ZipCode\Model\Resolver\DataProvider\Zipcode|PHPUnit\Framework\MockObject\MockObject
     */
    private $zipcodeDataProvider;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\ZipCode\Model\Resolver\Zipcode
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->zipcodeDataProvider = $this->createMock(\Auraine\ZipCode\Model\Resolver\DataProvider\Zipcode::class);
        $this->testObject = $this->objectManager->getObject(
        \Auraine\ZipCode\Model\Resolver\Zipcode::class,
            [
                'zipcodeDataProvider' => $this->zipcodeDataProvider,
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
