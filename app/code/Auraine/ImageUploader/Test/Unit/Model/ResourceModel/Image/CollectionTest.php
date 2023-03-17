<?php

// namespace Auraine\ImageUploader\Test\Unit\Model\ResourceModel\Image;

// use Auraine\ImageUploader\Model\Image;
// use Auraine\ImageUploader\Model\ResourceModel\Image as ResourceModelImage;
// use Auraine\ImageUploader\Model\ResourceModel\Image\Collection;
// use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
// use PHPUnit\Framework\TestCase;
// use Magento\Framework\App\ResourceConnection;
// use Magento\Framework\DB\Adapter\AdapterInterface;

// class CollectionTest extends TestCase
// {
//     /**
//      * @var Collection
//      */
//     protected $collection;

//     /**
//      * @inheritDoc
//      */
//     protected function setUp(): void
//     {
//         $objectManager = new ObjectManager($this);
    
//         $this->collection = $objectManager->getObject(
//             Collection::class,
//             [
//                 'connection' => $objectManager->getObject(AdapterInterface::class),
//                 'imageModel' => $objectManager->getObject(Image::class),
//                 'resourceModel' => $objectManager->getObject(ResourceModelImage::class),
//             ]
//         );
//     }
    

//     /**
//      * Test collection initialization
//      */
//     // public function testCollectionInitialization()
//     // {
//     //     $this->assertInstanceOf(Collection::class, $this->collection);
//     // }

//     public function test_Construct()
//     {
//         $collection = new Collection;

//         $collection->create
//     }
// }


namespace Auraine\ImageUploader\Test\Unit\Model\ResourceModel\Image;

use Auraine\ImageUploader\Model\ResourceModel\Image\Collection;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @var Collection
     */
    protected $collection;

    protected function setUp(): void
    {
        $this->collection = $this->getMockBuilder(Collection::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * Test the class instance and parent class.
     */
    public function testInstance()
    {
        $this->assertInstanceOf(Collection::class, $this->collection);
        $this->assertInstanceOf(\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection::class, $this->collection);
    }
}

