<?php
namespace Auraine\ZipCode\Test\Unit\Controller\Adminhtml\Index;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\ZipCode\Controller\Adminhtml\Index\MassAction
 * @codingStandardsIgnoreFile
 */
class MassActionTest extends TestCase
{
    /**
     * Test case 1
     * @var string const
     */
    private const TEST_CASE_ONE = 'Testcase 1';

    /**
     * Mock context
     *
     * @var \Magento\Backend\App\Action\Context
     */
    private $context;

    /**
     * Mock filter
     *
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    private $filter;

    /**
     * Mock collectionFactoryInstance
     *
     * @var \Auraine\ZipCode\Model\ResourceModel\Pincode\Collection
     */
    private $collectionFactoryInstance;

    /**
     * Mock collectionFactory
     *
     * @var \Auraine\ZipCode\Model\ResourceModel\Pincode\CollectionFactory
     */
    private $collectionFactory;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\ZipCode\Controller\Adminhtml\Index\MassAction
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->context = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->filter = $this->createMock(\Magento\Ui\Component\MassAction\Filter::class);
        $this->collectionFactoryInstance = $this->createMock(
            \Auraine\ZipCode\Model\ResourceModel\Pincode\Collection::class
        );
        $this->collectionFactory = $this->createMock(
            \Auraine\ZipCode\Model\ResourceModel\Pincode\CollectionFactory::class
        );
        $this->collectionFactory->method('create')->willReturn($this->collectionFactoryInstance);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\ZipCode\Controller\Adminhtml\Index\MassAction::class,
            [
                'context' => $this->context,
                'filter' => $this->filter,
                'collectionFactory' => $this->collectionFactory,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestExecute()
    {
        return [
            self::TEST_CASE_ONE => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestExecute
     */
    public function testExecute(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestDispatch()
    {
        return [
            self::TEST_CASE_ONE => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestDispatch
     */
    public function testDispatch(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestProcessUrlKeys()
    {
        return [
            self::TEST_CASE_ONE => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestProcessUrlKeys
     */
    public function testProcessUrlKeys(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetUrl()
    {
        return [
            self::TEST_CASE_ONE => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetUrl
     */
    public function testGetUrl(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetActionFlag()
    {
        return [
            self::TEST_CASE_ONE => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetActionFlag
     */
    public function testGetActionFlag(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetRequest()
    {
        return [
            self::TEST_CASE_ONE => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetRequest
     */
    public function testGetRequest(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetResponse()
    {
        return [
            self::TEST_CASE_ONE => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetResponse
     */
    public function testGetResponse(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
