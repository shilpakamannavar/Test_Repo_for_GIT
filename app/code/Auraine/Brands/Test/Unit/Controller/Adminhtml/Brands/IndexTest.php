<?php
// @codingStandardsIgnoreFile

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
     * Mock context
     *
     * @var \Magento\Backend\App\Action\Context|PHPUnit\Framework\MockObject\MockObject
     */
    private $context;

    /**
     * Mock resultPageFactoryInstance
     *
     * @var \Magento\Framework\View\Result\Page|PHPUnit\Framework\MockObject\MockObject
     */
    private $resultPageFactoryInstance;

    /**
     * Mock resultPageFactory
     *
     * @var \Magento\Framework\View\Result\PageFactory|PHPUnit\Framework\MockObject\MockObject
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
    public function dataProviderForTestGetResponse()
    {
        return [
            'Testcase 7' => [
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
