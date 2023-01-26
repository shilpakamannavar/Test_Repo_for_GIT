<?php
namespace Auraine\ZipCode\Test\Unit\Controller\Adminhtml\Index;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Auraine\Zipcode\Model\ResourceModel\Pincode\Collection;
use Auraine\Zipcode\Model\ResourceModel\Pincode\CollectionFactory;

/**
 * @covers \Auraine\ZipCode\Controller\Adminhtml\Index\MassStatusUpdate
 */
class MassStatusUpdateTest extends TestCase
{
    /**
     * Mock context
     *
     * @var \Magento\Backend\App\Action\Context|PHPUnit\Framework\MockObject\MockObject
     */
    private $context;

    /**
     * Mock filter
     *
     * @var \Magento\Ui\Component\MassAction\Filter|PHPUnit\Framework\MockObject\MockObject
     */
    private $filter;

    /**
     * Mock collectionFactoryInstance
     *
     * @var \Auraine\Zipcode\Model\ResourceModel\Pincode\Collection|PHPUnit\Framework\MockObject\MockObject
     */
    private $collectionFactoryInstance;

    /**
     * Mock collectionFactory
     *
     * @var \Auraine\Zipcode\Model\ResourceModel\Pincode\CollectionFactory|PHPUnit\Framework\MockObject\MockObject
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
     * @var \Auraine\ZipCode\Controller\Adminhtml\Index\MassStatusUpdate
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
        $this->collectionFactoryInstance = $this->createMock(Collection::class);
        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->collectionFactory->method('create')->willReturn($this->collectionFactoryInstance);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\ZipCode\Controller\Adminhtml\Index\MassStatusUpdate::class,
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
            'Testcase 1' => [
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
            'Testcase 1' => [
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
     * Data provider
     *
     * @return array
     */
    public function dataProviderForTestProcessUrlKeys()
    {
        return [
            'Testcase 1' => [
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
            'Testcase 1' => [
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
            'Testcase 1' => [
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
            'Testcase 1' => [
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
            'Testcase 1' => [
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
