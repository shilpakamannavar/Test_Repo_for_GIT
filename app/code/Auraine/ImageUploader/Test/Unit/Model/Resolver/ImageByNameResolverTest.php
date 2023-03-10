<?php
// @codingStandardsIgnoreFile
namespace Auraine\ImageUploader\Test\Unit\Model\Resolver;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\ImageUploader\Model\Resolver\ImageByNameResolver
 */
class ImageByNameResolverTest extends TestCase
{
    /**
     * Mock imageCollectionFactoryInstance
     *
     * @var \Auraine\ImageUploader\Model\ResourceModel\Image\Collection|PHPUnit\Framework\MockObject\MockObject
     */
    private $imageCollectionFactoryInstance;

    /**
     * Mock imageCollectionFactory
     *
     * @var \Auraine\ImageUploader\Model\ResourceModel\Image\CollectionFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $imageCollectionFactory;

    /**
     * Mock storeManager
     *
     * @var \Magento\Store\Model\StoreManagerInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $storeManager;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\ImageUploader\Model\Resolver\ImageByNameResolver
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->imageCollectionFactoryInstance = $this->createMock(\Auraine\ImageUploader\Model\ResourceModel\Image\Collection::class);
        $this->imageCollectionFactory = $this->createMock(\Auraine\ImageUploader\Model\ResourceModel\Image\CollectionFactory::class);
        $this->imageCollectionFactory->method('create')->willReturn($this->imageCollectionFactoryInstance);
        $this->storeManager = $this->createMock(\Magento\Store\Model\StoreManagerInterface::class);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\ImageUploader\Model\Resolver\ImageByNameResolver::class,
            [
                'imageCollectionFactory' => $this->imageCollectionFactory,
                'storeManager' => $this->storeManager,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestResolve()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestResolve
     */
    public function testResolve(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
