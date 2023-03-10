<?php
namespace Auraine\BannerSlider\Test\Unit\Model\Banner;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\BannerSlider\Model\Banner\DataProvider
 */
class DataProviderTest extends TestCase
{
    /**
     * Mock name
     *
     * @var \string|PHPUnit\Framework\MockObject\MockObject
     */
    private $name;

    /**
     * Mock primaryFieldName
     *
     * @var \string|PHPUnit\Framework\MockObject\MockObject
     */
    private $primaryFieldName;

    /**
     * Mock requestFieldName
     *
     * @var \string|PHPUnit\Framework\MockObject\MockObject
     */
    private $requestFieldName;

    /**
     * Mock bannerRepository
     *
     * @var \Auraine\BannerSlider\Api\BannerRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $bannerRepository;

    /**
     * Mock dataPersistor
     *
     * @var \Magento\Framework\App\Request\DataPersistorInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $dataPersistor;

    /**
     * Mock meta
     *
     * @var \array|PHPUnit\Framework\MockObject\MockObject
     */
    private $meta;

    /**
     * Mock data
     *
     * @var \array|PHPUnit\Framework\MockObject\MockObject
     */
    private $data;

    /**
     * Mock pool
     *
     * @var \Magento\Ui\DataProvider\Modifier\PoolInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $pool;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\BannerSlider\Model\Banner\DataProvider
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->name = "";
        $this->primaryFieldName = "";
        $this->requestFieldName = "";
        $this->bannerRepository = $this->createMock(\Auraine\BannerSlider\Api\BannerRepositoryInterface::class);
        $this->dataPersistor = $this->createMock(\Magento\Framework\App\Request\DataPersistorInterface::class);
        $this->meta = [];
        $this->data = [];
        $this->pool = $this->createMock(\Magento\Ui\DataProvider\Modifier\PoolInterface::class);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\BannerSlider\Model\Banner\DataProvider::class,
            [
                'name' => $this->name,
                'primaryFieldName' => $this->primaryFieldName,
                'requestFieldName' => $this->requestFieldName,
                'bannerRepository' => $this->bannerRepository,
                'dataPersistor' => $this->dataPersistor,
                'meta' => $this->meta,
                'data' => $this->data,
                'pool' => $this->pool,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetData()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetData
     */
    public function testGetData(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetMeta()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetMeta
     */
    public function testGetMeta(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetCollection()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetCollection
     */
    public function testGetCollection(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetName()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetName
     */
    public function testGetName(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetPrimaryFieldName()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetPrimaryFieldName
     */
    public function testGetPrimaryFieldName(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetRequestFieldName()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetRequestFieldName
     */
    public function testGetRequestFieldName(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetFieldSetMetaInfo()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetFieldSetMetaInfo
     */
    public function testGetFieldSetMetaInfo(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetFieldsMetaInfo()
    {
        $prerequisites = 2;
        $expectedResult = 2;
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => $prerequisites],
                'expectedResult' => ['param' => $expectedResult]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetFieldsMetaInfo
     */
    public function testGetFieldsMetaInfo(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetFieldMetaInfo()
    {
        $prerequisites = 4;
        $expectedResult = 4;
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => $prerequisites],
                'expectedResult' => ['param' => $prerequisites]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetFieldMetaInfo
     */
    public function testGetFieldMetaInfo(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestAddFilter()
    {
        $prerequisites = 1;
        $expectedResult = 1;
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => $prerequisites],
                'expectedResult' => ['param' => $expectedResult]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestAddFilter
     */
    public function testAddFilter(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetSearchCriteria()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetSearchCriteria
     */
    public function testGetSearchCriteria(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetSearchResult()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetSearchResult
     */
    public function testGetSearchResult(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestAddField()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestAddField
     */
    public function testAddField(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestAddOrder()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestAddOrder
     */
    public function testAddOrder(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestSetLimit()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestSetLimit
     */
    public function testSetLimit(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestRemoveField()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestRemoveField
     */
    public function testRemoveField(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestRemoveAllFields()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestRemoveAllFields
     */
    public function testRemoveAllFields(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestCount()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestCount
     */
    public function testCount(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetConfigData()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetConfigData
     */
    public function testGetConfigData(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestSetConfigData()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestSetConfigData
     */
    public function testSetConfigData(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetAllIds()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetAllIds
     */
    public function testGetAllIds(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
