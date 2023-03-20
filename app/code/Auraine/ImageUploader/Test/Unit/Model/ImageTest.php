<?php

namespace Auraine\ImageUploader\Test\Unit\Model;

use Auraine\ImageUploader\Model\Image;
use Auraine\ImageUploader\Model\ResourceModel\Image as ResourceModelImage;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var Image
     */
    protected $model;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        $resourceModelMock = $this->getMockBuilder(ResourceModelImage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->model = $this->objectManager->getObject(
            Image::class,
            [
                'resource' => $resourceModelMock
            ]
        );
    }

    public function testGetIdentities()
    {
        $this->model->setId(1);
        $this->assertEquals(['Auraine_images_1'], $this->model->getIdentities());
    }

    public function testGetPath()
    {
        $path = 'path/to/image.png';
        $this->model->setData('path', $path);
        $this->assertEquals($path, $this->model->getPath());
    }

    public function testSetPath()
    {
        $path = 'path/to/image.png';
        $this->model->setPath($path);
        $this->assertEquals($path, $this->model->getData('path'));
    }

    public function testGetName()
    {
        $name = 'image.png';
        $this->model->setData('name', $name);
        $this->assertEquals($name, $this->model->getName());
    }

    public function testSetName()
    {
        $name = 'image.png';
        $this->model->setName($name);
        $this->assertEquals($name, $this->model->getData('name'));
    }
}

