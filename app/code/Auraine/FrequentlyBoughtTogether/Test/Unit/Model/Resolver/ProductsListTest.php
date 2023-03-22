<?php
declare(strict_types=1);

namespace Auraine\FrequentlyBoughtTogether\Test\Unit\Model\Resolver;

use Auraine\FrequentlyBoughtTogether\Model\Resolver\ProductsList;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use PHPUnit\Framework\TestCase;
use Magento\Framework\GraphQl\Query\Uid;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class ProductsListTest extends TestCase
{
    /**
     * Cache instance
     *
     * @var CacheInterface
     */
    protected $cacheMock;

    /**
     * Serializer instance
     *
     * @var Json
     */
    protected $jsonMock;

    /**
     * Resource Connection instance
     *
     * @var ResourceConnection
     */
    protected $resourceConnectionMock;

    /**
     * Uid Encoder instance
     *
     * @var uid
     */
    protected $uidEncoderMock;

    /**
     * Resolver instance
     *
     * @var resolver
     */
    private $resolver;

    /**
     * Main setUp method for the test
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->cacheMock = $this->createMock(CacheInterface::class);
        $this->jsonMock = $this->createMock(Json::class);
        $this->resourceConnectionMock = $this->createMock(ResourceConnection::class);
        $this->uidEncoderMock = $this->createMock(Uid::class);
        
        $this->resolver = new ProductsList(
            $this->cacheMock,
            $this->jsonMock,
            $this->resourceConnectionMock,
            $this->uidEncoderMock
        );
    }

    public function testResove()
    {
        $cacheKey = ProductsList::CACHE_KEY_PREFIX.'test';
        $cacheMock = $this->createMock(CacheInterface::class);
        $cacheMock->method('load')->with($cacheKey)->willReturn('success');
        $args = ['uid' => 'abc123'];

        $productUid = $args['uid'];

        $this->uidEncoderMock->method('decode')
            ->with($productUid)
            ->willReturn('prod-uid');

        $orderItems = [
            ['sku' => 'SKU1'],
            ['sku' => 'SKU2'],
            ['sku' => 'SKU3'],
            ['sku' => 'SKU4'],
            ['sku' => 'SKU5'],
            ['sku' => 'SKU6'],
            ['sku' => 'SKU7'],
            ['sku' => 'SKU8'],
            ['sku' => 'SKU9'],
            ['sku' => 'SKU10'],
            ['sku' => 'SKU11'],
            ['sku' => 'SKU12'],
        ];
    
        $connection = $this->createMock(\Magento\Framework\DB\Adapter\AdapterInterface::class);
        $connection->expects($this->once())
            ->method('getTableName')
            ->with('sales_order_item')
            ->willReturn('sales_order_item');
        $connection->expects($this->once())
            ->method('fetchAll')
            ->willReturn($orderItems);
        $this->resourceConnectionMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($connection);

        $this->jsonMock->method('serialize')
            ->with(
                ['SKU1','SKU2','SKU3','SKU4','SKU5','SKU6','SKU7','SKU8','SKU9','SKU10','SKU11','SKU12']
            );

        $this->jsonMock->method('unserialize')
            ->with('success')
            ->willReturn(
                ['SKU1','SKU2','SKU3','SKU4','SKU5','SKU6','SKU7','SKU8','SKU9','SKU10','SKU11','SKU12']
            );

        $field = $this->createMock(Field::class);
        $context = [];
        $info = $this->createMock(ResolveInfo::class);
        $value = [];

        $this->resolver->resolve($field, $context, $info, $value, $args);
    }

    /**
     * @dataProvider dataProviderForGetProductUidThrowsException
     */
    public function testGetProductUidThrowsException(array $args)
    {
        $this->expectException(GraphQlInputException::class);
        $this->resolver->getProductUid($args);
    }

    public function dataProviderForGetProductUidThrowsException()
    {
        return [
            [['uid' => null]],
            [['randomArg' => 'randomValue']],
            [[]],
        ];
    }

    public function testGetProductUidReturnsUid()
    {
        $args = ['uid' => 'abc123'];
        $result = $this->resolver->getProductUid($args);
        $this->assertEquals('abc123', $result);
    }

    public function testGetMostBoughtTogether(): void
    {
        $id = 123; // replace with a valid product ID for testing
        $orderItems = [
            ['sku' => 'SKU1'],
            ['sku' => 'SKU2'],
            ['sku' => 'SKU3'],
            ['sku' => 'SKU4'],
            ['sku' => 'SKU5'],
            ['sku' => 'SKU6'],
            ['sku' => 'SKU7'],
            ['sku' => 'SKU8'],
            ['sku' => 'SKU9'],
            ['sku' => 'SKU10'],
            ['sku' => 'SKU11'],
            ['sku' => 'SKU12'],
        ];
    
        $connection = $this->createMock(\Magento\Framework\DB\Adapter\AdapterInterface::class);
        $connection->expects($this->once())
            ->method('getTableName')
            ->with('sales_order_item')
            ->willReturn('sales_order_item');
        $connection->expects($this->once())
            ->method('fetchAll')
            ->willReturn($orderItems);
        $this->resourceConnectionMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($connection);
    
        $expectedResult = ['SKU1','SKU2','SKU3','SKU4','SKU5','SKU6','SKU7','SKU8','SKU9','SKU10','SKU11','SKU12'];
        $result = $this->resolver->getMostBoughtTogether($id);
        $this->assertSame($expectedResult, $result);
    }
}
