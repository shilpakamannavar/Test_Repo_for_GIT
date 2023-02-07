<?php
declare(strict_types=1);
namespace Auraine\SellerPanel\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Authorization\Model\UserContextInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;

class Attributes extends AbstractModifier
{
    const STATUS = 'status';
    const PRODUCT_STATUS = 'seller_product_status';
    /**
     * @var Magento\Framework\Stdlib\ArrayManager
     */
    private $arrayManager;
    /**
     * @var UserContextInterface
     */
    protected $userContext;
    /**
     * @var UserCollectionFactory
     */
    private $userCollectionFactory;

    /**
     * @param ArrayManager $arrayManager
     */
     /**
      * Constructor
      *
      * @param ArrayManager $arrayManager
      * @param UserContextInterface $userContext
      * @param UserCollectionFactory $userCollectionFactory
      */
    public function __construct(
        ArrayManager $arrayManager,
        UserContextInterface $userContext,
        UserCollectionFactory $userCollectionFactory,
    ) {
        $this->arrayManager = $arrayManager;
        $this->userContext = $userContext;
        $this->userCollectionFactory = $userCollectionFactory;
    }

    /**
     * ModifyData
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * ModifyMeta
     *
     * @param array $data
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        $collection = $this->userCollectionFactory->create();
        $userId = $this->userContext->getUserId();
        $collection->addFieldToFilter('main_table.user_id', $userId);
        $userData = $collection->getFirstItem();
        $roleName = $userData->getRoleName();
        $path = $this->arrayManager->findPath(self::STATUS, $meta, null, 'children');
          $pathProductStatus = $this->arrayManager->findPath(self::PRODUCT_STATUS, $meta, null, 'children');
        if ($roleName == 'supplier' || $roleName == 'Supplier') {
            $meta = $this->arrayManager->set(
                "{$path}/arguments/data/config/disabled",
                $meta,
                true
            );
            $meta = $this->arrayManager->set(
                "{$pathProductStatus}/arguments/data/config/disabled",
                $meta,
                true
            );
        } else {
            $meta = $this->arrayManager->set(
                "{$path}/arguments/data/config/enabled",
                $meta,
                true
            );
            $meta = $this->arrayManager->set(
                "{$pathProductStatus}/arguments/data/config/enabled",
                $meta,
                true
            );
        }
        return $meta;
    }
}
