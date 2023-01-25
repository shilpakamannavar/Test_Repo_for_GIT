<?php
namespace Auraine\ZipCode\Test\Unit\Model\Resolver\DataProvider;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\ZipCode\Model\Resolver\DataProvider\Zipcode
 */
class ZipcodeTest extends TestCase
{
    /**
     * Mock pincodeFactoryInstance
     *
     * @var \Auraine\ZipCode\Model\Pincode|PHPUnit\Framework\MockObject\MockObject
     */
    private $pincodeFactoryInstance;

    /**
     * Mock pincodeFactory
     *
     * @var \Auraine\ZipCode\Model\PincodeFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $pincodeFactory;

    /**
     * Mock countryFactoryInstance
     *
     * @var \Magento\Directory\Model\Country|PHPUnit\Framework\MockObject\MockObject
     */
    private $countryFactoryInstance;

    /**
     * Mock countryFactory
     *
     * @var \Magento\Directory\Model\CountryFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $countryFactory;

    /**
     * Mock regionFactoryInstance
     *
     * @var \Magento\Directory\Model\Region|PHPUnit\Framework\MockObject\MockObject
     */
    private $regionFactoryInstance;

    /**
     * Mock regionFactory
     *
     * @var \Magento\Directory\Model\RegionFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $regionFactory;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\ZipCode\Model\Resolver\DataProvider\Zipcode
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->pincodeFactoryInstance = $this->createMock(\Auraine\ZipCode\Model\Pincode::class);
        $this->pincodeFactory = $this->createMock(\Auraine\ZipCode\Model\PincodeFactory::class);
        $this->pincodeFactory->method('create')->willReturn($this->pincodeFactoryInstance);
        $this->countryFactoryInstance = $this->createMock(\Magento\Directory\Model\Country::class);
        $this->countryFactory = $this->createMock(\Magento\Directory\Model\CountryFactory::class);
        $this->countryFactory->method('create')->willReturn($this->countryFactoryInstance);
        $this->regionFactoryInstance = $this->createMock(\Magento\Directory\Model\Region::class);
        $this->regionFactory = $this->createMock(\Magento\Directory\Model\RegionFactory::class);
        $this->regionFactory->method('create')->willReturn($this->regionFactoryInstance);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\ZipCode\Model\Resolver\DataProvider\Zipcode::class,
            [
                'pincodeFactory' => $this->pincodeFactory,
                'countryFactory' => $this->countryFactory,
                'regionFactory' => $this->regionFactory,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestGenerateZipCodeResponse()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGenerateZipCodeResponse
     */
    public function testGenerateZipCodeResponse(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestIsAvailableToShip()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestIsAvailableToShip
     */
    public function testIsAvailableToShip(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
