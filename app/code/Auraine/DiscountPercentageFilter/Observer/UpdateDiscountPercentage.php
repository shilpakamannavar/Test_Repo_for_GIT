<?php

namespace Auraine\DiscountPercentageFilter\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Auraine\DiscountPercentageFilter\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\Action;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;

class UpdateDiscountPercentage implements ObserverInterface
{
    /**
     * @var Data
     */
    private $dataHelper;

    /**
     * @var Action
     */
    private $action;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Constructor
     *
     * @param Data $dataHelper
     * @param Action $action
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Data $dataHelper,
        Action $action,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager
    ) {
        $this->dataHelper = $dataHelper;
        $this->action = $action;
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * Update Discount Value
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getProduct();
        $productType = $product->getTypeId();
        if (!empty($product['sku'])) {
            $sku = $product['sku'];
            $productPrice =  $product['price'];
            $productSpPrice =  $product['special_price'];
            $productData = $this->productRepository->get($sku);
            if ($productPrice !=0 && $productPrice != null && $productType == 'simple') {
                if ($productSpPrice >= $productPrice) {
                    $this->action->updateAttributes([
                        $productData->getEntityId()
                    ], [
                        'discount' => null
                    ], $this->getStoreIds());
                }
                $discountPercentage = 100 - round(($productSpPrice / $productPrice)*100);
                $discountVar = $this->dataHelper->getDiscountVar($discountPercentage);
                if ($discountVar) {
                    $discountId = $product->getResource()
                    ->getAttribute("discount")
                    ->getSource()
                    ->getOptionId($discountVar);
                    $this->action->updateAttributes([
                        $productData->getEntityId()
                    ], [
                        'discount' => $discountId
                    ], $this->getStoreIds());
                }
            }
        }
    }

    /**
     * Get Store Id
     *
     * @return void
     */
    public function getStoreIds()
    {
        return $this->storeManager->getStore()->getId();
    }
}
