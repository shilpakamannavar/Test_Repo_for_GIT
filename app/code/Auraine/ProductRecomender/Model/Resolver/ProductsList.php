<?php
declare(strict_types=1);
namespace Auraine\ProductRecomender\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\Uid;
use Magento\Framework\App\ObjectManager;
use Magento\Sales\Api\Data\OrderInterface;

class ProductsList implements ResolverInterface
{
  /** orderCollectionFactory
   *
   * @var $orderCollectionFactory
   */
    protected $orderCollectionFactory;
  /** OrderRepository
   *
   * @var orderRepository
   */
    protected $orderRepository;

  /** @var Uid */
    private $uidEncoder;

    /**
     * Order object cache
     * @var OrderInterface
     */
    private $_order;

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
        $this->orderCollectionFactory = $orderCollectionFactory;
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
          $orderCollection = $this->orderCollectionFactory->create()->addAttributeToSelect('*');
          $orderData = $orderCollection->getData();
          return $this->getMostBoughtTogether($productId, $orderData);
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
            $orderId = $order['entity_id'];
            $order = $this->getOrder($orderId);
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
    /**
     * Get Order
     *
     * @param int $orderId
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @throws LocalizedException
     */
    public function getOrder($orderId)
    {
        if ($this->_order) {

            return $this->_order;

        }
        try {
            $this->_order = $this->orderRepository->get($orderId);
            return $this->_order;
        } catch (NoSuchEntityException $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__('This order no longer exists.'));
        }
    }
}
