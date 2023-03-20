<?php
declare(strict_types=1);

namespace Auraine\ImageUploader\Test\Unit\Model\Resolver;

use Auraine\ImageUploader\Model\Resolver\ImageByNameResolver;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Auraine\ImageUploader\Model\ResourceModel\Image\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\Serializer\Json;
use PHPUnit\Framework\TestCase;

class ImageByNameResolverTest extends TestCase
{
    private $objectManager;
    private $collectionFactory;
    private $storeManager;
    private $urlInterface;
    private $cacheInterface;
    private $jsonSerializer;
    private $field;
    private $context;
    private $resolveInfo;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->collectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeManager = $this->getMockBuilder(StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlInterface = $this->getMockBuilder(UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cacheInterface = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->jsonSerializer = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->field = $this->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->context = [];

        $this->resolveInfo = $this->getMockBuilder(ResolveInfo::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function invalidArgumentsDataProvider()
    {
        return [
            [['image_id' => 1]],
        ];
    }

    public function testResolveReturnsDataFromCache()
    {
        $args = ['image_id' => 1];
       $cacheKey = 'auraine_imageuploader_1';
       $cachedData = '{"image_id":1,"path":"http://localhost:8080/media/test/image.jpg","name":"Test Image"}';
       $expectedData = json_decode($cachedData, true);
       $resolver = $this->objectManager->getObject(
        ImageByNameResolver::class,
        [
            'imageCollectionFactory' => $this->collectionFactory,
            'storeManager' => $this->storeManager,
            'cache' => $this->cacheInterface,
            'json' => $this->jsonSerializer,
        ]
    );

    $this->cacheInterface->expects($this->once())
        ->method('load')
        ->with($cacheKey)
        ->willReturn($cachedData);

    $this->jsonSerializer->expects($this->once())
        ->method('unserialize')
        ->with($cachedData)
        ->willReturn($expectedData);

    $result = $resolver->resolve($this->field, $this->context, $this->resolveInfo, [], $args);

    $this->assertEquals($expectedData, $result);
    }

    public function testResolveReturnsDataFromDatabase()
     {
    $args = ['image_id' => 1];
    $cacheKey = 'auraine_imageuploader_1';
    $expectedData = [
        'image_id' => 1,
        'path' => 'http://localhost:8080/media/test/image.jpg',
        'name' => 'Test Image',
    ];
    $collection = $this->getMockBuilder(\Auraine\ImageUploader\Model\ResourceModel\Image\Collection::class)
        ->disableOriginalConstructor()
        ->getMock();
    $imageModel = $this->getMockBuilder(\Auraine\ImageUploader\Model\Image::class)
        ->disableOriginalConstructor()
        ->getMock();

    $resolver = $this->objectManager->getObject(
        ImageByNameResolver::class,
        [
            'imageCollectionFactory' => $this->collectionFactory,
            'storeManager' => $this->storeManager,
            'cache' => $this->cacheInterface,
            'json' => $this->jsonSerializer,
        ]
    );

    $this->cacheInterface->expects($this->once())
        ->method('load')
        ->with($cacheKey)
        ->willReturn(false);

    $this->collectionFactory->expects($this->once())
        ->method('create')
        ->willReturn($collection);

    $collection->expects($this->once())
        ->method('addFieldToFilter')
        ->with('image_id', $args['image_id'])
        ->willReturnSelf();


    $result = $resolver->resolve($this->field, $this->context, $this->resolveInfo, [], $args);

    $this->assertEquals([], $result);
}
}
