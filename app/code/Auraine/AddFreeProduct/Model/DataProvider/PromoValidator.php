<?php
namespace Auraine\AddFreeProduct\Model\DataProvider;

use Amasty\Promo\Model\ItemRegistry\PromoItemRegistry;

class PromoValidator
{
    /**
     * Item qty
     * @var string const
     */
    public const KEY_QTY_ITEM_PREFIX = 'ampromo_qty_select_';

    /**
     * @var PromoItemRegistry
     */
    private $promoItemRegistry;

    /**
     * @param PromoItemRegistry $promoItemRegistry
     */
    public function __construct(
        PromoItemRegistry $promoItemRegistry,
    ) {
        $this->promoItemRegistry = $promoItemRegistry;
    }

    /**
     * Checks promo item add to quote
     *
     * @param array $items
     * @param array $itemsForAdd
     * @return bool
     */
    public function isPromoItemsAddedInQuote(array $items, array $itemsForAdd): bool
    {
        $addedItems = 0;
        foreach ($items as $item) {
            if (in_array($item->getProduct()->getData('sku'), $itemsForAdd, true)) {
                $addedItems++;
            }
        }

        return (bool)$addedItems;
    }

    /**
     * Promo item data is filtering by rule_id and sku instead only by sku
     *
     * @param string $sku
     * @param array $params
     *
     * @return \Amasty\Promo\Model\ItemRegistry\PromoItemData|null
     * @since 2.5.0 promo item data is filtering by rule_id and sku instead only by sku
     */
    public function getPromoDataItem($sku, $params)
    {
        
        if (isset($params['rule_id']) && $ruleId = (int)$params['rule_id']) {
            $promoItemData = $this->promoItemRegistry->getItemBySkuAndRuleId($sku, $ruleId);
            if ($promoItemData && $promoItemData->getQtyToProcess() > 0) {
                return $promoItemData;
            }
        } else {
            $promoItemsData = $this->promoItemRegistry->getItemsBySku($sku);
            foreach ($promoItemsData as $promoItemData) {
                if ($promoItemData->getQtyToProcess() > 0) {
                    return $promoItemData;
                }
            }
        }

        return null;
    }

    /**
     * Get quantity to add.
     *
     * @param \Amasty\Promo\Model\ItemRegistry\PromoItemData $promoDataItem
     * @param array $params
     * @param int $productId
     *
     * @return float
     */
    public function getQtyToAdd($promoDataItem, $params, $productId)
    {
        $qty = $promoDataItem->getQtyToProcess();
        if (isset($params[self::KEY_QTY_ITEM_PREFIX . $productId])
            && $params[self::KEY_QTY_ITEM_PREFIX . $productId] <= $qty
        ) {
            $qty = $params[self::KEY_QTY_ITEM_PREFIX . $productId];
        }

        return (float)$qty;
    }
}
