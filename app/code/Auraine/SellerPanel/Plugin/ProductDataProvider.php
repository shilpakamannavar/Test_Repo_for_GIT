<?php
declare(strict_types=1);
namespace Auraine\SellerPanel\Plugin;

use Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider as RelatedProductDataProvider;
use Magento\Authorization\Model\UserContextInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;
use Magento\Catalog\Model\Product\Attribute\Repository as ProductAttributeRepo;

class ProductDataProvider extends RelatedProductDataProvider
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
     * @var prodAttribute
     */
    private $prodAttribute;
  /**
   * Constructor
   *
   * @param UserContextInterface $userContext
   * @param UserCollectionFactory $userCollectionFactory
   * @param ProductAttributeRepo $productAttributeRepo
   */
    public function __construct(
        UserContextInterface $userContext,
        UserCollectionFactory $userCollectionFactory,
        ProductAttributeRepo $productAttributeRepo
    ) {

        $this->userContext = $userContext;
        $this->userCollectionFactory = $userCollectionFactory;
        $this->prodAttribute = $productAttributeRepo;
    }
  /**
   * AroundGetData
   *
   * @param ProductDataProvider $subject
   */
    public function aroundGetData(
        \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider $subject,
        callable $proceed
    ) {
        $collection = $this->userCollectionFactory->create();
        $userId = $this->userContext->getUserId();
        $collection->addFieldToFilter('main_table.user_id', $userId);
        $userData = $collection->getFirstItem();
        $roleName = $userData->getRoleName();
        $userName = $userData->getUserName();
        $selectOptions = $this->prodAttribute->get('seller_list')->getOptions();
        $sellerAttributeValueFinal = '';
        foreach ($selectOptions as $selectOption) {
            $sellerAttributeValue = $selectOption['value'];
            $sellerAttributeLabel = $selectOption['label'];
            if ($userName == $sellerAttributeLabel) {
                $sellerAttributeValueFinal = $sellerAttributeValue;
                break;
            }
        }
        if ($roleName == 'supplier' || $roleName == 'Supplier') {
            $subject->getCollection()->clear();
            if ($sellerAttributeValueFinal && ($sellerAttributeValueFinal!="")) {
                $subject->getCollection()
                        ->addFieldToFilter('seller_list', ['eq' => $sellerAttributeValueFinal])->load();
                $subject->getCollection()
                         ->addFieldToFilter('seller_list', ['eq' => $sellerAttributeValueFinal])->toArray();
                $subject->getCollection()
                        ->addFieldToFilter('seller_list', ['eq' => $sellerAttributeValueFinal])->load()->getSize();
            }
        }
        return $proceed();
    }
}
