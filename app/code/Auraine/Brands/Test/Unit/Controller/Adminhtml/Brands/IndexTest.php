<?php
namespace Auraine\Brands\Test\Unit\Controller\Adminhtml\Brands;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\Brands\Controller\Adminhtml\Brands\Index
 */
class IndexTest extends TestCase
{
    /**
     * Test case 1
     * @var string const
     */
    private const TEST_CASE_BRANDS = 'Testcase brands';

    /**
     * Mock context
     *
     * @var \Magento\Backend\App\Action\Context
     */
    private $context;

    /**
     * Mock resultPageFactoryInstance
     *
     * @var \Magento\Framework\View\Result\Page
     */
    private $resultPageFactoryInstance;

    /**
     * Mock resultPageFactory
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\Brands\Controller\Adminhtml\Brands\Index
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->context = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->resultPageFactoryInstance = $this->createMock(\Magento\Framework\View\Result\Page::class);
        $this->resultPageFactory = $this->createMock(\Magento\Framework\View\Result\PageFactory::class);
        $this->resultPageFactory->method('create')->willReturn($this->resultPageFactoryInstance);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\Brands\Controller\Adminhtml\Brands\Index::class,
            [
                'context' => $this->context,
                'resultPageFactory' => $this->resultPageFactory,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestExecute()
    {
        return [
            self::TEST_CASE_BRANDS => [
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
    public function dataProviderForTestGetResponse()
    {
        return [
            self::TEST_CASE_BRANDS => [
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
