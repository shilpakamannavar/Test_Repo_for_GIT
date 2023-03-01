<?php
// @codingStandardsIgnoreFile

namespace Auraine\Brands\Test\Unit\Controller\Adminhtml\Brands;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\Brands\Controller\Adminhtml\Brands\AddRow
 */
class AddRowTest extends TestCase
{
    /**
     * Mock context
     *
     * @var \Magento\Backend\App\Action\Context|PHPUnit\Framework\MockObject\MockObject
     */
    private $context;

    /**
     * Mock coreRegistry
     *
     * @var \Magento\Framework\Registry|PHPUnit\Framework\MockObject\MockObject
     */
    private $coreRegistry;

    /**
     * Mock gridFactoryInstance
     *
     * @var \Auraine\Brands\Model\Grid|PHPUnit\Framework\MockObject\MockObject
     */
    private $gridFactoryInstance;

    /**
     * Mock gridFactory
     *
     * @var \Auraine\Brands\Model\GridFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $gridFactory;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\Brands\Controller\Adminhtml\Brands\AddRow
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
        $this->gridFactoryInstance = $this->createMock(\Auraine\Brands\Model\Grid::class);
        $this->gridFactory = $this->createMock(\Auraine\Brands\Model\GridFactory::class);
        $this->gridFactory->method('create')->willReturn($this->gridFactoryInstance);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\Brands\Controller\Adminhtml\Brands\AddRow::class,
            [
                'context' => $this->context,
                'coreRegistry' => $this->coreRegistry,
                'gridFactory' => $this->gridFactory,
            ]
        );
    }
    /**
     * @return array
     */
    public function dataProviderForTestGetRequest()
    {
        return [
            'Testcase 6' => [
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
}
