<?php
// @codingStandardsIgnoreFile

namespace Auraine\Brands\Test\Unit\Controller\Adminhtml\Brands;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Auraine\Brands\Model\Grid;

/**
 * @covers \Auraine\Brands\Controller\Adminhtml\Brands\Save
 */
class SaveTest extends TestCase
{
    /**
     * Mock context
     *
     * @var \Magento\Backend\App\Action\Context|PHPUnit\Framework\MockObject\MockObject
     */
    private $context;
    /**
     * Mock Grid
     *
     * @var Auraine\Brands\Model\Grid
     */
    protected $grid;
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
     * Mock fileUploaderFactoryInstance
     *
     * @var \Magento\MediaStorage\Model\File\Uploader|PHPUnit\Framework\MockObject\MockObject
     */
    private $fileUploaderFactoryInstance;

    /**
     * Mock fileUploaderFactory
     *
     * @var \Magento\MediaStorage\Model\File\UploaderFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $fileUploaderFactory;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\Brands\Controller\Adminhtml\Brands\Save
     */
    private $testObject;

    public const TEST_TITLE="test title";
    public const TEST_DESCRIPTION="test description";
    public const TEST_IMG="test.png";



    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->context = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->gridFactoryInstance = $this->createMock(\Auraine\Brands\Model\Grid::class);
        $this->gridFactory = $this->createMock(\Auraine\Brands\Model\GridFactory::class);
        $this->gridFactory->method('create')->willReturn($this->gridFactoryInstance);
        $this->fileUploaderFactoryInstance = $this->createMock(\Magento\MediaStorage\Model\File\Uploader::class);
        $this->fileUploaderFactory = $this->createMock(\Magento\MediaStorage\Model\File\UploaderFactory::class);
        $this->fileUploaderFactory->method('create')->willReturn($this->fileUploaderFactoryInstance);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\Brands\Controller\Adminhtml\Brands\Save::class,
            [
                'context' => $this->context,
                'gridFactory' => $this->gridFactory,
                'fileUploaderFactory' => $this->fileUploaderFactory,
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
        $data = $this->getBrandData();
         $this->assertEquals(TEST_TITLE, $data['title']);
         $this->assertEquals(TEST_DESCRIPTION, $data['description']);
         $this->assertEquals(1, $data['status']);
         $this->assertEquals(TEST_IMG, $data['image']);
         $this->assertEquals(1, $data['is_popular']);
         $this->assertEquals(1, $data['is_featured']);
         $this->assertEquals(1, $data['is_exclusive']);
         $this->assertEquals(1, $data['is_justin']);
    }

     /**
      * @return array
      */
    protected function getBrandData()
    {
        return  [
            'title' => 'test title',
            'description' => 'test description',
            'status' => 1,
            'image' => "test.png",
            'is_popular' => 1,
            'is_featured' => 1,
            'is_exclusive' => 1,
            'is_justin' => 1,
              
        ];
      
    }
}
