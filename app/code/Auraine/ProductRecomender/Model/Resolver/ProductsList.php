<?php
declare(strict_types=1);
namespace Auraine\ProductRecomender\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\Uid;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;

class ProductsList implements ResolverInterface
{
   /** @var Uid */
    private $uidEncoder;

    /**
     * @param ResourceConnection $resourceConnection
     * @param Uid|null $uidEncoder
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        Uid $uidEncoder = null
    ) {
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
          $productId = $this->getProductId($args);
          return $this->getMostBoughtTogether($productId);
    }
    /**
     * GetProductId
     *
     * @param array $args
     * @return int
     * @throws GraphQlInputException
     */
    private function getProductId(array $args): int
    {
        if (!isset($args['id'])) {
            throw new GraphQlInputException(__('"Product id should be specified'));
        }

        return (int)$args['id'];
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
        $query = 'select product_id, count(product_id) as total_Prod_Count from '.$table.' where product_id in
        (select product_id from sales_order_item where order_id in
        ((select order_id from sales_order_item where product_id='.$id.'))
        and product_id != '.$id.') group by  product_id';
        $orderItems = $connection->fetchAll($query);
        $orderItemsUid = [];
        foreach ($orderItems as $item) {
            $prodUID = $this->uidEncoder->encode((string)$item['total_Prod_Count']);
            array_push($orderItemsUid, $prodUID);
        }
        return $orderItemsUid;
    }
}
