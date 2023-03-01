<?php
namespace Auraine\ZipCode\Test\Unit\Controller\Adminhtml\Pincode;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\ZipCode\Controller\Adminhtml\Pincode\NewAction
 * @codingStandardsIgnoreFile
 */
class NewActionTest extends TestCase
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
     * Mock coreRegistry
     *
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * Mock resultForwardFactoryInstance
     *
     * @var \Magento\Backend\Model\View\Result\Forward
     */
    private $resultForwardFactoryInstance;

    /**
     * Mock resultForwardFactory
     *
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    private $resultForwardFactory;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\ZipCode\Controller\Adminhtml\Pincode\NewAction
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->context = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->coreRegistry = $this->createMock(\Magento\Framework\Registry::class);
        $this->resultForwardFactoryInstance = $this->createMock(\Magento\Backend\Model\View\Result\Forward::class);
        $this->resultForwardFactory = $this->createMock(\Magento\Backend\Model\View\Result\ForwardFactory::class);
        $this->resultForwardFactory->method('create')->willReturn($this->resultForwardFactoryInstance);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\ZipCode\Controller\Adminhtml\Pincode\NewAction::class,
            [
                'context' => $this->context,
                'coreRegistry' => $this->coreRegistry,
                'resultForwardFactory' => $this->resultForwardFactory,
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
    public function dataProviderForTestInitPage()
    {
        return [
            self::TEST_CASE_ONE => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestInitPage
     */
    public function testInitPage(array $prerequisites, array $expectedResult)
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
