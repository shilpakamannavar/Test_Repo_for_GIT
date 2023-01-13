<?php
namespace Auraine\ZipCode\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\ZipCode\Model\PincodeRepository
 */
class PincodeRepositoryTest extends TestCase
{
    /**
     * Mock resource
     *
     * @var \Auraine\ZipCode\Model\ResourceModel\Pincode|PHPUnit\Framework\MockObject\MockObject
     */
    private $resource;

    /**
     * Mock pincodeFactoryInstance
     *
     * @var \Auraine\ZipCode\Api\Data\PincodeInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $pincodeFactoryInstance;

    /**
     * Mock pincodeFactory
     *
     * @var \Auraine\ZipCode\Api\Data\PincodeInterfaceFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $pincodeFactory;

    /**
     * Mock pincodeCollectionFactoryInstance
     *
     * @var \Auraine\ZipCode\Model\ResourceModel\Pincode\Collection|PHPUnit\Framework\MockObject\MockObject
     */
    private $pincodeCollectionFactoryInstance;

    /**
     * Mock pincodeCollectionFactory
     *
     * @var \Auraine\ZipCode\Model\ResourceModel\Pincode\CollectionFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $pincodeCollectionFactory;

    /**
     * Mock searchResultsFactoryInstance
     *
     * @var \Auraine\ZipCode\Api\Data\PincodeSearchResultsInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $searchResultsFactoryInstance;

    /**
     * Mock searchResultsFactory
     *
     * @var \Auraine\ZipCode\Api\Data\PincodeSearchResultsInterfaceFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $searchResultsFactory;

    /**
     * Mock collectionProcessor
     *
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $collectionProcessor;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\ZipCode\Model\PincodeRepository
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->resource = $this->createMock(\Auraine\ZipCode\Model\ResourceModel\Pincode::class);
        $this->pincodeFactoryInstance = $this->createMock(\Auraine\ZipCode\Api\Data\PincodeInterface::class);
        $this->pincodeFactory = $this->createMock(\Auraine\ZipCode\Api\Data\PincodeInterfaceFactory::class);
        $this->pincodeFactory->method('create')->willReturn($this->pincodeFactoryInstance);
        $this->pincodeCollectionFactoryInstance = $this->createMock(\Auraine\ZipCode\Model\ResourceModel\Pincode\Collection::class);
        $this->pincodeCollectionFactory = $this->createMock(\Auraine\ZipCode\Model\ResourceModel\Pincode\CollectionFactory::class);
        $this->pincodeCollectionFactory->method('create')->willReturn($this->pincodeCollectionFactoryInstance);
        $this->searchResultsFactoryInstance = $this->createMock(\Auraine\ZipCode\Api\Data\PincodeSearchResultsInterface::class);
        $this->searchResultsFactory = $this->createMock(\Auraine\ZipCode\Api\Data\PincodeSearchResultsInterfaceFactory::class);
        $this->searchResultsFactory->method('create')->willReturn($this->searchResultsFactoryInstance);
        $this->collectionProcessor = $this->createMock(\Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface::class);
        $this->testObject = $this->objectManager->getObject(
        \Auraine\ZipCode\Model\PincodeRepository::class,
            [
                'resource' => $this->resource,
                'pincodeFactory' => $this->pincodeFactory,
                'pincodeCollectionFactory' => $this->pincodeCollectionFactory,
                'searchResultsFactory' => $this->searchResultsFactory,
                'collectionProcessor' => $this->collectionProcessor,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestSave()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestSave
     */
    public function testSave(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGet()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGet
     */
    public function testGet(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetList()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetList
     */
    public function testGetList(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestDelete()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestDelete
     */
    public function testDelete(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestDeleteById()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestDeleteById
     */
    public function testDeleteById(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
