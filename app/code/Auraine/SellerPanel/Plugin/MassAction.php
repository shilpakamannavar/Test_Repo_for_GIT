<?php
declare(strict_types=1);
namespace Auraine\SellerPanel\Plugin;

use Magento\Catalog\Ui\Component\Product\MassAction as ProductMassAction;
use Magento\Framework\AuthorizationInterface;
use Magento\Authorization\Model\UserContextInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;

class MassAction
{
    /**
     * @var UserContextInterface
     */
    protected $userContext;
    /**
     * @var UserCollectionFactory
     */
    private $userCollectionFactory;
    /**
     * @var AuthorizationInterface
     */
    private $authorization;
    /**
     * Constructor
     *
     * @param UserContextInterface $userContext
     * @param UserCollectionFactory $userCollectionFactory
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        UserContextInterface $userContext,
        UserCollectionFactory $userCollectionFactory,
        AuthorizationInterface $authorization
    ) {
        $this->userContext = $userContext;
        $this->userCollectionFactory = $userCollectionFactory;
        $this->authorization = $authorization;
    }
    /**
     * AfterIsActionAllowed
     *
     * @param ProductMassAction $subject
     */
    public function afterIsActionAllowed(ProductMassAction $subject, $result, $actionType)
    {
        $collection = $this->userCollectionFactory->create();
        $userId = $this->userContext->getUserId();
        $collection->addFieldToFilter('main_table.user_id', $userId);
        $userData = $collection->getFirstItem();
        $roleName = $userData->getRoleName();
        if ($roleName == 'supplier' || $roleName == 'Supplier') {
            if (($actionType == "delete")) {
                return false;
            }
        }
        return $result;
    }
}
