<?php
declare(strict_types=1);
namespace Auraine\ProductRecomender\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\Uid;
use Magento\Framework\App\ObjectManager;

class ProductsList implements ResolverInterface
{
  /** _orderCollectionFactory
   *
   * @var _orderCollectionFactory
   */
    protected $_orderCollectionFactory;
  /** OrderRepository
   *
   * @var orderRepository
   */
    protected $orderRepository;

  /** @var Uid */
    private $uidEncoder;

  /** Constructor function
   *
   * @param CollectionFactory $orderCollectionFactory
   * @param OrderRepositoryInterface $orderRepository
   * @param Uid|null $uidEncoder
   */
    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        Uid $uidEncoder = null
    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository = $orderRepository;
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
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
          $productId = $this->getProductId($args);
          $orderCollection = $this->_orderCollectionFactory->create()->addAttributeToSelect('*');
          $orderData = $orderCollection->getData();
          $mostBought = $this->getMostBoughtTogether($productId, $orderData);
          return $mostBought;
    }
    /**
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
    private function getMostBoughtTogether(int $id, $orders): array
    {
        $orderItems = [];
        foreach ($orders as $order) {
            $order_id = $order['entity_id'];
            $order = $this->orderRepository->get($order_id);
            if ($this->hasItemInOrder($id, $order)) {
                foreach ($order->getAllItems() as $item) {

                    if ($id === (int) $item->getProductId()) {
                        continue;
                    }
                    $orderItems[$item->getProductId()] = isset($orderItems[$item->getProductId()]) ?
                       (int) $orderItems[$item->getProductId()] + (int) $item->getQtyOrdered() :
                       (int) $item->getQtyOrdered();
                }
            }
        }
        arsort($orderItems);
        // get only id in array index
        $orderItems = array_keys($orderItems);
        $orderItemsUid = [];
        foreach ($orderItems as $item) {
            $prodUID = $this->uidEncoder->encode((string)$item);
            array_push($orderItemsUid, $prodUID);
        }
        return $orderItemsUid;
    }
   /**
    * Has item in these orders.
    */
    private function hasItemInOrder(int $id, $order): bool
    {
        foreach ($order->getAllItems() as $item) {
            if ($id === (int) $item->getProductId()) {
                return true;
            }
        }

        return false;
    }
}
