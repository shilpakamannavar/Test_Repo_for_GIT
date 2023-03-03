<?php
declare(strict_types=1);
namespace Auraine\ProductRecomender\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\Uid;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\Serializer\Json;

class ProductsList implements ResolverInterface
{
   /** @var Uid */
    private $uidEncoder;

      /**
     * Cache instance
     *
     * @var CacheInterface
     */
    protected $cache;

    /**
     * Serializer instance
     *
     * @var Json
     */
    protected $json;

    /**
     * Cache key prefix
     */
    const CACHE_KEY_PREFIX = 'auraine_productList_';
    /**
     * @param ResourceConnection $resourceConnection
     * @param Uid|null $uidEncoder
     */
    public function __construct(
        CacheInterface $cache,
        Json $json,
        ResourceConnection $resourceConnection,
        Uid $uidEncoder = null,
        
    ) {
        $this->cache = $cache;
        $this->json = $json;
        $this->resourceConnection = $resourceConnection;
        $this->uidEncoder = $uidEncoder ?: ObjectManager::getInstance()
          ->get(Uid::class);
       
    }

    /**
     * Resolver function for frequent product
     *
     * @param Field $field, $context, ResolveInfo $info, array $value , array $args
     *
     * @return dataProvider
     */
    /**
     * Resolve
     *
     * @param Field $field
     * @param Context $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array|\Magento\Framework\GraphQl\Query\Resolver\Value|mixed
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $cacheKey = self::CACHE_KEY_PREFIX . md5(json_encode($args));
        $cachedData = $this->cache->load($cacheKey);

        if ($cachedData) {
            $result = $this->json->unserialize($cachedData);
        } else {

            $cacheLifetime = 86400; // 1 day
            $productUid = $this->getProductUid($args);
            $pId = $this->uidEncoder->decode((string)$productUid);
            $result =  $this->getMostBoughtTogether((int)$pId);
            $this->cache->save($this->json->serialize($result), $cacheKey, [], $cacheLifetime);
        }
       
        return $result;
    }
    /**
     * GetProductUid
     *
     * @param array $args
     * @return int
     * @throws GraphQlInputException
     */
    private function getProductUid(array $args): string
    {
        if (!isset($args['uid'])) {
            throw new GraphQlInputException(__('"Product Uid should be specified'));
        }
        return (string)$args['uid'];
    }
    /**
     * Get frequently items bought together
     */
    /**
     * GetMostBoughtTogether
     *
     * @param int $id
     * @return array
     */
    private function getMostBoughtTogether(int $id): array
    {
        $connection = $this->resourceConnection->getConnection();
        $table = $connection->getTableName('sales_order_item');
        $query = 'select sku from '.$table.' where product_id in
        (select product_id from sales_order_item where order_id in
        ((select order_id from sales_order_item where product_id='.$id.'))
        and product_id != '.$id.') group by  sku limit 12';
        $orderItems = $connection->fetchAll($query);
        $skus = [];
        foreach ($orderItems as $item) {
            array_push($skus, $item['sku']);
        }
        return $skus;
    }
}
