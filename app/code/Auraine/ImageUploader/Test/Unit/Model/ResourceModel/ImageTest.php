<?php

namespace Auraine\ImageUploader\Test\Unit\Model\ResourceModel;

use Auraine\ImageUploader\Model\ResourceModel\Image;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    /**
     * @var Image
     */
    protected $model;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        // Mock the ResourceConnection class
        $resourceConnection = $this->getMockBuilder(ResourceConnection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->model = $objectManager->getObject(
            Image::class,
            [
                'resource' => $resourceConnection,
            ]
        );
    }

    /**
     * Test class instantiation
     */
    public function testInstance()
    {
        $this->assertInstanceOf(Image::class, $this->model);
    }

    /**
     * Test primary field name
     */
    public function testPrimaryFieldName()
    {
        $this->assertEquals('image_id', $this->model->getIdFieldName());
    }
}